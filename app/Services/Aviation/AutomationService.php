<?php
namespace App\Services\Aviation;
use App\Models\Aviation\{Aircraft,Flight,MaintenancePlan,MaintenanceTask,PilotLeaveRule,CrewMember,CrewAbsence,SafetyEvent};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/** Maintenance and leave thresholds come from the operator's configured programme. */
class AutomationService
{
    private function actualFlights()
    {
        return Flight::where('status','!=','cancelled')->whereNotNull('actual_off_at')
            ->whereNotNull('actual_on_at')->where('actual_on_at','<=',now('UTC'));
    }
    public function aircraftUsage(MaintenancePlan $plan): array
    {
        $flights=$this->actualFlights()->where('aircraft_id',$plan->aircraft_id)
            ->where('actual_on_at','>',$plan->tracking_from)->get();
        return ['minutes'=>(int)$plan->baseline_minutes + (int)ceil($flights->sum(fn($f)=>max(0,$f->actual_off_at->diffInSeconds($f->actual_on_at))/60)),
            'cycles'=>(int)$plan->baseline_cycles + (int)$flights->sum('actual_landings')];
    }
    public function maintenanceState(MaintenancePlan $plan, int $extraMinutes=0, int $extraCycles=0, ?Carbon $at=null): array
    {
        $usage=$this->aircraftUsage($plan); $at=$at ?: now('UTC');
        $remainingMinutes=$plan->interval_minutes ? $plan->last_minutes+$plan->interval_minutes-$usage['minutes']-$extraMinutes : null;
        $remainingCycles=$plan->interval_cycles ? $plan->last_cycles+$plan->interval_cycles-$usage['cycles']-$extraCycles : null;
        $dueAt=$plan->interval_days ? $plan->last_completed_at->copy()->addDays($plan->interval_days) : null;
        $due=($remainingMinutes!==null && $remainingMinutes<=0) || ($remainingCycles!==null && $remainingCycles<=0) || ($dueAt && $at->gte($dueAt));
        $upcoming=$due || ($remainingMinutes!==null && $remainingMinutes<=$plan->warning_minutes)
            || ($remainingCycles!==null && $remainingCycles<=$plan->warning_cycles) || ($dueAt && $at->copy()->addDays($plan->warning_days)->gte($dueAt));
        return $usage+['remaining_minutes'=>$remainingMinutes,'remaining_cycles'=>$remainingCycles,'due_at'=>$dueAt,
            'status'=>$due?'due':($upcoming?'upcoming':'healthy')];
    }
    public function pilotMinutes(PilotLeaveRule $rule): int
    {
        $from=$rule->last_leave_end ?: $rule->tracking_from;
        $flights=$this->actualFlights()->where('actual_on_at','>',$from)
            ->whereHas('duty.crew',fn($q)=>$q->where('aviation_crew.id',$rule->crew_id))->get();
        return ($rule->last_leave_end ? 0 : (int)$rule->baseline_minutes) + (int)ceil($flights->sum(function($f) use ($from) {
            return max(0,$f->actual_on_at->timestamp-max($from->timestamp,$f->actual_off_at->timestamp))/60;
        }));
    }
    public function sync(): void
    {
        MaintenancePlan::where('active',true)->orderBy('id')->each(function($plan) {
            DB::transaction(function() use ($plan) {
                Aircraft::whereKey($plan->aircraft_id)->lockForUpdate()->firstOrFail();
                $plan=MaintenancePlan::whereKey($plan->id)->lockForUpdate()->firstOrFail();
                $state=$this->maintenanceState($plan);
                $task=$plan->tasks()->whereNull('completed_at')->first();
                $task=$task ?: new MaintenanceTask(['plan_id'=>$plan->id]);
                $task->fill(['status'=>$state['status'],'due_at'=>$state['due_at']])->save();
            });
        });
        PilotLeaveRule::where('active',true)->orderBy('id')->each(function($rule) {
            DB::transaction(function() use ($rule) {
                CrewMember::whereKey($rule->crew_id)->lockForUpdate()->firstOrFail();
                $rule=PilotLeaveRule::whereKey($rule->id)->lockForUpdate()->firstOrFail();
                $latest=$rule->absences()->orderByDesc('ends_at')->first();
                if ($latest && $latest->ends_at->isFuture()) return;
                if ($latest && (!$rule->last_leave_end || $rule->last_leave_end->lt($latest->ends_at)))
                    $rule->update(['last_leave_end'=>$latest->ends_at]);
                if ($this->pilotMinutes($rule)<$rule->threshold_minutes) return;
                // Do not silently defer leave behind future flights. Block availability immediately,
                // finish any duty already underway, and flag conflicting published rosters.
                $start=now('UTC');
                $current=$rule->crew->duties()->where('status','published')->get()->filter(fn($d)=>$d->beginning()->lte($start) && $d->ending()->gt($start));
                foreach ($current as $duty) if ($duty->ending()->gt($start)) $start=$duty->ending()->copy();
                $end=$start->copy()->addDays($rule->leave_days);
                $key='leave:'.$rule->id.':'.($rule->last_leave_end?->timestamp ?: $rule->tracking_from->timestamp);
                $absence=CrewAbsence::firstOrCreate(['automation_key'=>$key],['crew_id'=>$rule->crew_id,'leave_rule_id'=>$rule->id,
                    'reason'=>'Flight-hour recovery leave','starts_at'=>$start,'ends_at'=>$end,
                    'notes'=>'Automatically scheduled under '.$rule->source_reference.'. Threshold: '.($rule->threshold_minutes/60).' flight hours.']);
                if ($absence->wasRecentlyCreated) foreach ($rule->crew->duties()->where('status','published')->get() as $duty) {
                    if ($duty->beginning()->lt($end) && $duty->ending()->gt($start)) SafetyEvent::create([
                        'duty_id'=>$duty->id,'event_type'=>'automatic_leave_conflict',
                        'issues'=>['Pilot #'.$rule->crew_id.' has automatic recovery leave. Replace this pilot or revise the roster.']]);
                }
            });
        });
    }
    public function maintenanceIssues(Flight $flight): array
    {
        if ($flight->actual_on_at) return []; // Historical actuals are evidence, not projections.
        $issues=[];
        $planned=Flight::where('aircraft_id',$flight->aircraft_id)->where('id','!=',$flight->id ?: 0)
            ->where('status','scheduled')->whereNull('actual_on_at')->get()->push($flight)
            ->sortBy(fn($f)=>$f->departure_at->timestamp);
        foreach (MaintenancePlan::where('aircraft_id',$flight->aircraft_id)->where('active',true)->get() as $plan) {
            $minutes=0; $cycles=0;
            foreach ($planned as $leg) {
                if ($leg->ending()->lte($plan->last_completed_at)) continue;
                $minutes+=(int)ceil($leg->departure_at->diffInSeconds($leg->arrival_at)/60);
                $cycles+=$leg->planned_landings;
                $state=$this->maintenanceState($plan,$minutes,$cycles,$leg->ending());
                if ($state['status']==='due') {
                    $issues[]='Maintenance due or reached by this flight or a later scheduled leg: '.$plan->name.'. Complete maintenance before release.';
                    break;
                }
            }
            if ($plan->tasks()->whereNull('completed_at')->where('scheduled_start','<',$flight->ending())
                ->where('scheduled_end','>',$flight->beginning())->exists()) $issues[]='Aircraft overlaps booked maintenance: '.$plan->name;
        }
        return $issues;
    }
    public function pilotLeaveIssues(CrewMember $crew, $flights, Carbon $end): array
    {
        $rule=PilotLeaveRule::where('crew_id',$crew->id)->where('active',true)->first();
        if (!$rule) return [];
        $first=$flights->sortBy(fn($f)=>$f->beginning()->timestamp)->first();
        if (!$first) return [];
        // Future rosters may start after already scheduled recovery leave.
        $leave=$rule->absences()->where('ends_at','<=',$first->beginning())->orderByDesc('ends_at')->first();
        if ($leave && (!$rule->last_leave_end || $leave->ends_at->gt($rule->last_leave_end)))
            $rule->last_leave_end=$leave->ends_at;
        $used=$this->pilotMinutes($rule);
        $epoch=$rule->last_leave_end ?: $rule->tracking_from;
        // Project all earlier published assignments as well.
        $candidateIds=$flights->pluck('id');
        $planned=Flight::where('status','scheduled')->whereNull('actual_on_at')->whereNotIn('id',$candidateIds)
            ->where('arrival_at','>',now('UTC'))->where('departure_at','>=',$epoch)
            ->whereHas('duty.crew',fn($q)=>$q->where('aviation_crew.id',$crew->id))->get();
        $nextLeave=$rule->absences()->where('starts_at','>=',$end)->orderBy('starts_at')->first();
        if ($nextLeave) $planned=$planned->filter(fn($f)=>$f->departure_at->lt($nextLeave->starts_at));
        $projected=(int)ceil($planned->sum(fn($f)=>$f->departure_at->diffInSeconds($f->arrival_at)/60)) +
            (int)ceil($flights->whereNull('actual_on_at')->sum(fn($f)=>$f->departure_at->diffInSeconds($f->arrival_at)/60));
        return ($used>=$rule->threshold_minutes || $used+$projected>$rule->threshold_minutes)
            ? ['Pilot flight-hour leave threshold reached or exceeded by this roster. Schedule recovery leave before further flights.'] : [];
    }
}
