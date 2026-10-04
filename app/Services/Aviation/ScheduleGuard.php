<?php

namespace App\Services\Aviation;

use App\Models\Aviation\{CrewMember,Duty,Flight,FatiguePolicy};
use Carbon\Carbon;

/** All scheduling instants are UTC; periods use actual times once recorded. */
class ScheduleGuard
{
    public function flight(Flight $flight): array
    {
        $errors = [];
        $flight->loadMissing('aircraft.type','origin','destination');
        $from = $flight->beginning(); $to = $flight->ending();
        if (!$from || !$to || $to->lessThanOrEqualTo($from)) return ['Arrival must follow departure (UTC).'];
        if ($flight->origin_id === $flight->destination_id) $errors[] = 'Origin and destination must differ.';
        if (!$flight->origin?->active || !$flight->destination?->active) $errors[] = 'Both airports must be active.';
        $errors = array_merge($errors, app(AutomationService::class)->maintenanceIssues($flight));
        $aircraft = $flight->aircraft;
        if (!$aircraft || $aircraft->status !== 'active') return [...$errors, 'Aircraft is not active.'];
        foreach (['airworthiness_expires_at'=>'Airworthiness certificate', 'insurance_expires_at'=>'Aircraft insurance'] as $key=>$label) {
            if (!$aircraft->{$key} || $aircraft->{$key}->lt($to->copy()->startOfDay())) $errors[] = "$label is missing or expired.";
        }
        if ($aircraft->groundings()->where('starts_at','<',$to)->where('ends_at','>',$from)->exists())
            $errors[] = 'Aircraft is grounded during this flight.';

        $others = Flight::where('aircraft_id', $aircraft->id)
            ->when($flight->exists, fn($q) => $q->where('id','!=',$flight->id))
            ->whereIn('status',['scheduled','operated'])->get();
        foreach ($others as $other) {
            if ($from->lt($other->ending()) && $to->gt($other->beginning())) {
                $errors[] = "Aircraft overlaps flight {$other->flight_number}."; break;
            }
        }
        $before = $others->filter(fn($f) => $f->ending()->lte($from))->sortByDesc(fn($f) => $f->ending()->timestamp)->first();
        $after = $others->filter(fn($f) => $f->beginning()->gte($to))->sortBy(fn($f) => $f->beginning()->timestamp)->first();
        if ($before && $before->destination_id !== $flight->origin_id) $errors[] = 'Aircraft is not at the departure airport after its previous scheduled flight.';
        if ($after && $after->origin_id !== $flight->destination_id) $errors[] = 'Aircraft cannot reach its next scheduled departure airport.';
        if ($aircraft->type) {
            $turn=(int)$aircraft->type->minimum_turnaround_minutes;
            if ($before && $before->ending()->diffInMinutes($from)<$turn) $errors[] = 'Aircraft turnaround after the previous flight is too short.';
            if ($after && $to->diffInMinutes($after->beginning())<$turn) $errors[] = 'Aircraft turnaround before the next flight is too short.';
        } else $errors[] = 'Aircraft type is not configured.';
        return array_values(array_unique($errors));
    }

    public function duty(Duty $duty): array
    {
        $duty->loadMissing('crew.staff','crew.qualifications','flights.aircraft.type','activities','pilotPolicy','cabinPolicy');
        $errors = [];
        $start = $duty->beginning(); $end = $duty->ending();
        if ($end->lte($start)) return ['Duty release must follow reporting time (UTC).'];
        if ($duty->crew->isEmpty()) $errors[] = 'Assign crew before publishing.';
        foreach ($duty->activities as $activity) {
            if ($activity->ends_at->lte($activity->starts_at) || $activity->starts_at->lt($start) || $activity->ends_at->gt($end))
                $errors[] = "{$activity->type} activity must occur within the duty period.";
        }
        $flights = $duty->flights->where('status','!=','cancelled')->sortBy(fn($f) => $f->beginning()->timestamp)->values();
        if ($duty->type === 'flight' && $flights->isEmpty()) $errors[] = 'Flight duty needs at least one flight leg.';
        if ($duty->type !== 'flight' && $flights->isNotEmpty()) $errors[] = 'Flights require a flight duty period.';
        $previous = null;
        foreach ($flights as $flight) {
            foreach ($this->flight($flight) as $issue) $errors[] = "{$flight->flight_number}: $issue";
            if ($flight->beginning()->lt($start) || $flight->ending()->gt($end)) $errors[] = "{$flight->flight_number}: flight lies outside the duty period.";
            if ($previous && $flight->beginning()->lt($previous->ending())) $errors[] = 'Flight legs overlap in this duty.';
            if ($previous && $previous->destination_id !== $flight->origin_id) $errors[] = 'Crew cannot reach the next flight departure airport.';
            $previous = $flight;
        }

        $pilotCount = $duty->crew->filter(fn($c) => $c->crew_category === 'pilot')->count();
        $cabinCount = $duty->crew->filter(fn($c) => $c->crew_category === 'cabin')->count();
        foreach ($flights as $flight) {
            if (!$flight->aircraft?->type) { $errors[] = 'Flight aircraft type is missing.'; continue; }
            $type = $flight->aircraft->type;
            if ($pilotCount < $type->minimum_pilots || $cabinCount < $type->minimum_cabin_crew)
                $errors[] = "{$flight->flight_number}: crew complement below aircraft type requirements.";
        }
        if ($flights->isNotEmpty() && $duty->crew->where('pivot.role','captain')->count() !== 1)
            $errors[] = 'A flight duty must have exactly one captain.';

        foreach ($duty->crew as $crew) {
            $name = trim(($crew->staff?->first_name ?? 'Crew').' '.($crew->staff?->last_name ?? '#'.$crew->id));
            $role = $crew->pivot->role;
            foreach (app(AutomationService::class)->pilotLeaveIssues($crew,$flights,$end) as $issue) $errors[] = "$name: $issue";
            if ($crew->status !== 'active' || $crew->staff?->status !== 'active') $errors[] = "$name is not active.";
            if (($crew->crew_category === 'pilot') !== in_array($role,['captain','first_officer'],true))
                $errors[] = "$name has an incompatible roster role.";
            foreach (['licence_expires_at'=>'Licence/certificate','medical_expires_at'=>'Medical'] as $field=>$label) {
                if (!$crew->{$field} || $crew->{$field}->lt($end->copy()->startOfDay())) $errors[] = "$name: $label is missing or expired.";
            }
            if ($crew->absences()->where('starts_at','<',$end)->where('ends_at','>',$start)->exists())
                $errors[] = "$name is unavailable during this duty.";
            $restRecords = $crew->restPeriods()->where('starts_at','<=',$end->copy()->addMonths(13))
                ->where('ends_at','>=',$start->copy()->subMonths(13))->get();
            if ($restRecords->contains(fn($r) => $r->starts_at->lt($end) && $r->ends_at->gt($start)))
                $errors[] = "$name: declared rest overlaps this duty.";
            $policy = $crew->crew_category === 'pilot' ? $duty->pilotPolicy : $duty->cabinPolicy;
            if (!$policy || $policy->crew_category !== $crew->crew_category || !$policy->ready()
                || ($policy->effective_from && $policy->effective_from->gt($start->copy()->startOfDay()))
                || ($policy->effective_until && $policy->effective_until->lt($end->copy()->startOfDay()))) {
                $errors[] = "$name: an approved, complete {$crew->crew_category} fatigue policy covering the duty is required.";
                continue;
            }
            foreach ($flights as $flight) {
                $qualified = $crew->qualifications->first(fn($q) => $q->aircraft_type_id === $flight->aircraft?->aircraft_type_id &&
                    $q->role === $role && $q->expires_at && $q->expires_at->gte($flight->ending()->copy()->startOfDay()));
                if (!$qualified) $errors[] = "$name: valid $role qualification is missing for {$flight->flight_number}.";
            }
            $existing = $crew->duties()->whereIn('status',['published','completed'])
                ->when($duty->exists, fn($q) => $q->where('aviation_duties.id','!=',$duty->id))
                ->where('report_at','>=',$start->copy()->subMonths(13))
                ->where('report_at','<=',$end->copy()->addMonths(13))->with('flights')->get();
            foreach ($existing as $other) {
                if ($start->lt($other->ending()) && $end->gt($other->beginning())) $errors[] = "$name overlaps duty {$other->reference}.";
                $gap = $end->lte($other->beginning()) ? $end->diffInMinutes($other->beginning()) :
                    ($start->gte($other->ending()) ? $other->ending()->diffInMinutes($start) : null);
                if ($gap !== null && $gap < $policy->min_rest_minutes)
                    $errors[] = "$name: insufficient rest around {$other->reference}.";
                if ($gap !== null && $gap >= $policy->min_rest_minutes) {
                    $restStart = $end->lte($other->beginning()) ? $end : $other->ending();
                    $restEnd = $end->lte($other->beginning()) ? $other->beginning() : $start;
                    if (!$this->hasDocumentedRest($restRecords,$restStart,$restEnd,(int)$policy->min_rest_minutes))
                        $errors[] = "$name: documented rest is missing around {$other->reference}.";
                }
            }
            if (!$existing->contains(fn($other) => $other->ending()->lte($start)) &&
                !$restRecords->contains(fn($r) => $r->ends_at->lte($start) &&
                    $r->starts_at->diffInMinutes($r->ends_at) >= $policy->min_rest_minutes &&
                    ($r->status==='confirmed' || $r->ends_at->isFuture())))
                $errors[] = "$name: record a sufficient rest period before the first rostered duty.";
            $duration = $start->diffInMinutes($end);
            if ($duration > $policy->max_duty_minutes) $errors[] = "$name: duty exceeds its maximum duration.";
            if ($flights->count() > $policy->max_sectors_per_duty) $errors[] = "$name: too many flight sectors in this duty.";
            if ($flights->sum(fn($f) => $f->actual_landings ?? $f->planned_landings) > $policy->max_landings_per_duty)
                $errors[] = "$name: landings exceed the duty limit.";
            if ($flights->isNotEmpty() && $start->diffInMinutes($flights->last()->ending()) > $policy->max_fdp_minutes)
                $errors[] = "$name: flight duty period exceeds its maximum.";

            $duties = $existing->map(fn($d) => [$d->beginning(),$d->ending()])->all();
            $duties[] = [$start,$end];
            $candidateDuty = [[$start,$end]];
            foreach ([7=>'max_duty_7d_minutes',28=>'max_duty_28d_minutes'] as $days=>$key)
                if ($this->exceeds($duties,$candidateDuty,(int)$policy->{$key},$days,'days'))
                    $errors[] = "$name: duty time exceeds the rolling $days-day limit.";
            if ($this->exceedsCalendarMonths($duties,$candidateDuty,(int)$policy->max_duty_12mo_minutes))
                $errors[] = "$name: duty time exceeds the rolling 12-calendar-month limit.";

            if ($crew->crew_category !== 'pilot' || $flights->isEmpty()) continue;
            $history = [];
            foreach ($existing as $other) foreach ($other->flights->where('status','!=','cancelled') as $leg)
                $history[] = [$leg->beginning(),$leg->ending()];
            $candidate = $flights->map(fn($leg) => [$leg->beginning(),$leg->ending()])->all();
            $history = array_merge($history,$candidate);
            $single = $pilotCount === 1;
            foreach ([['max_flight_24h_'.($single?'single':'multi').'_minutes',24,'hours','24-hour'],
                ['max_flight_7d_minutes',7,'days','7-day'],['max_flight_28d_minutes',28,'days','28-day']] as [$field,$length,$unit,$label]) {
                if ($this->exceeds($history,$candidate,(int)$policy->{$field},$length,$unit))
                    $errors[] = "$name: flight time exceeds the rolling $label limit.";
            }
            if ($this->exceedsCalendarMonths($history,$candidate,(int)$policy->max_flight_12mo_minutes))
                $errors[] = "$name: flight time exceeds the rolling 12-calendar-month limit.";
        }
        return array_values(array_unique($errors));
    }

    private function hasDocumentedRest($records, Carbon $from, Carbon $to, int $minutes): bool
    {
        return $records->contains(fn($r) => $r->starts_at->gte($from) && $r->ends_at->lte($to)
            && $r->starts_at->diffInMinutes($r->ends_at) >= $minutes
            && ($r->status==='confirmed' || $r->ends_at->isFuture()));
    }

    /** Continuous overlap, not the sum of entire flights merely touching a window. */
    private function exceeds(array $intervals, array $candidate, int $limitMinutes, int $length, string $unit): bool
    {
        $ends = [];
        foreach ($intervals as [$a,$b]) {
            $ends[] = $b->copy();
            $ends[] = $unit === 'days' ? $a->copy()->addDays($length) : $a->copy()->addHours($length);
        }
        foreach ($ends as $end) {
            $begin = $unit === 'days' ? $end->copy()->subDays($length) : $end->copy()->subHours($length);
            if (!collect($candidate)->contains(fn($span) => $span[0]->lt($end) && $span[1]->gt($begin))) continue;
            $seconds = 0;
            foreach ($intervals as [$a,$b]) $seconds += max(0, min($b->timestamp,$end->timestamp) - max($a->timestamp,$begin->timestamp));
            if ($seconds > $limitMinutes * 60) return true;
        }
        return false;
    }

    /** Twelve complete consecutive calendar months, rather than 365 days. */
    private function exceedsCalendarMonths(array $intervals, array $candidate, int $limitMinutes): bool
    {
        foreach ($candidate as [$a,$b]) {
            $month = $a->copy()->startOfMonth();
            while ($month->lt($b)) {
                for ($back=0;$back<12;$back++) {
                    $start=$month->copy()->subMonths($back); $end=$start->copy()->addMonths(12);
                    $seconds=0;
                    foreach ($intervals as [$x,$y])
                        $seconds+=max(0,min($y->timestamp,$end->timestamp)-max($x->timestamp,$start->timestamp));
                    if ($seconds>$limitMinutes*60) return true;
                }
                $month->addMonth();
            }
        }
        return false;
    }
}
