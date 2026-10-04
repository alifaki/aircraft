<?php

namespace Tests\Feature;

use App\Models\{Branch,Company,Staff};
use App\Models\Aviation\{Aircraft,AircraftType,Airport,CrewMember,Duty,FatiguePolicy,Flight,Qualification,RestPeriod};
use App\Services\Aviation\ScheduleGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AviationSchedulingTest extends TestCase
{
    use RefreshDatabase;

    private function data(): array
    {
        $company=Company::create(['app_id'=>'TEST-AIR','company_name'=>'Test Operator','description'=>'Testing',
            'location'=>'Airport','city'=>'Test City','country'=>'Tanzania','email'=>'test@example.org',
            'phone'=>'+255700000001','status'=>'active']);
        $branch=Branch::create(['company_id'=>$company->id,'branch_name'=>'Base','branch_code'=>'BASE',
            'location'=>'Airport','city'=>'Test City','country'=>'Tanzania','status'=>'active']);
        $staff=Staff::create(['branch_id'=>$branch->id,'section_id'=>1,'employee_number'=>'CREW001',
            'first_name'=>'Test','last_name'=>'Pilot','phone'=>'+255700000002','status'=>'active']);
        $a=Airport::create(['icao'=>'HTZA','name'=>'Airport A','city'=>'A','country'=>'Tanzania','timezone'=>'Africa/Dar_es_Salaam','active'=>true]);
        $b=Airport::create(['icao'=>'HTZB','name'=>'Airport B','city'=>'B','country'=>'Tanzania','timezone'=>'Africa/Dar_es_Salaam','active'=>true]);
        $type=AircraftType::create(['icao_designator'=>'C208','model'=>'Test aircraft','minimum_pilots'=>1,'minimum_cabin_crew'=>0,'minimum_turnaround_minutes'=>30]);
        $plane=Aircraft::create(['registration'=>'5H-TEST','aircraft_type_id'=>$type->id,'status'=>'active',
            'airworthiness_expires_at'=>now()->addYears(2),'insurance_expires_at'=>now()->addYears(2)]);
        $pilot=CrewMember::create(['staff_id'=>$staff->id,'crew_category'=>'pilot','licence_number'=>'TEST-LIC',
            'licence_expires_at'=>now()->addYears(2),'medical_expires_at'=>now()->addYears(2),'status'=>'active']);
        Qualification::create(['crew_id'=>$pilot->id,'aircraft_type_id'=>$type->id,'role'=>'captain',
            'expires_at'=>now()->addYears(2)]);
        $policy=FatiguePolicy::create(['name'=>'Approved test policy','crew_category'=>'pilot',
            'source_reference'=>'TEST/APPROVED/001','effective_from'=>now()->subYear(),
            'effective_until'=>now()->addYear(),'status'=>'approved','approved_at'=>now(),
            'max_flight_24h_single_minutes'=>480,'max_flight_24h_multi_minutes'=>480,
            'max_flight_7d_minutes'=>2040,'max_flight_28d_minutes'=>6000,
            'max_flight_12mo_minutes'=>60000,'max_fdp_minutes'=>720,'max_duty_minutes'=>780,
            'max_duty_7d_minutes'=>6000,'max_duty_28d_minutes'=>16000,'max_duty_12mo_minutes'=>108000,
            'min_rest_minutes'=>600,'max_sectors_per_duty'=>3,'max_landings_per_duty'=>3]);
        return compact('a','b','plane','pilot','policy');
    }

    private function duty(array $data, int $offsetDays, int $flightMinutes, string $status='draft'): Duty
    {
        $start=now('UTC')->addDays($offsetDays)->startOfDay()->addHours(8);
        $off=$start->copy()->addMinutes(30); $on=$off->copy()->addMinutes($flightMinutes);
        $end=$on->copy()->addMinutes(30);
        $duty=Duty::create(['reference'=>'D-'.$offsetDays,'type'=>'flight','report_at'=>$start,
            'release_at'=>$end,'pilot_policy_id'=>$data['policy']->id,'status'=>$status]);
        $duty->crew()->attach($data['pilot']->id,['role'=>'captain']);
        $origin=$offsetDays%2===0?$data['a']:$data['b'];
        $destination=$offsetDays%2===0?$data['b']:$data['a'];
        Flight::create(['flight_number'=>'TEST'.$offsetDays,'flight_date'=>$off->toDateString(),
            'leg_sequence'=>1,
            'aircraft_id'=>$data['plane']->id,'origin_id'=>$origin->id,'destination_id'=>$destination->id,
            'duty_id'=>$duty->id,'departure_at'=>$off,'arrival_at'=>$on,
            'planned_landings'=>1,'status'=>$status==='published'?'scheduled':'draft']);
        RestPeriod::create(['crew_id'=>$data['pilot']->id,'starts_at'=>$start->copy()->subHours(12),
            'ends_at'=>$start->copy()->subHour(),'status'=>'planned']);
        return $duty;
    }

    public function test_unapproved_fatigue_policy_prevents_publication(): void
    {
        $data=$this->data(); $data['policy']->update(['status'=>'draft','approved_at'=>null]);
        $issues=app(ScheduleGuard::class)->duty($this->duty($data,1,120));
        $this->assertTrue(collect($issues)->contains(fn($issue)=>str_contains($issue,'approved, complete')));
    }

    public function test_qualified_resting_pilot_and_available_aircraft_pass_scheduling_checks(): void
    {
        $data=$this->data();
        $issues=app(ScheduleGuard::class)->duty($this->duty($data,1,120));
        $this->assertSame([], $issues);
    }

    public function test_eight_hour_window_blocks_a_nine_hour_single_pilot_flight(): void
    {
        $data=$this->data();
        $issues=app(ScheduleGuard::class)->duty($this->duty($data,1,540));
        $this->assertTrue(collect($issues)->contains(fn($issue)=>str_contains($issue,'24-hour limit')));
    }

    public function test_seven_day_rolling_window_sums_distinct_duties(): void
    {
        $data=$this->data();
        for ($day=1;$day<=4;$day++) $this->duty($data,$day,420,'published');
        $issues=app(ScheduleGuard::class)->duty($this->duty($data,5,420));
        $this->assertTrue(collect($issues)->contains(fn($issue)=>str_contains($issue,'rolling 7-day limit')));
    }

    public function test_aircraft_cannot_be_scheduled_on_overlapping_flights(): void
    {
        $data=$this->data(); $scheduled=$this->duty($data,1,180,'published')->flights()->firstOrFail();
        $candidate=Flight::create(['flight_number'=>'OVERLAP','flight_date'=>$scheduled->flight_date,
            'leg_sequence'=>1,
            'aircraft_id'=>$data['plane']->id,'origin_id'=>$data['a']->id,'destination_id'=>$data['b']->id,
            'departure_at'=>$scheduled->departure_at->copy()->addMinutes(30),
            'arrival_at'=>$scheduled->arrival_at->copy()->addMinutes(30),'planned_landings'=>1,'status'=>'draft']);
        $issues=app(ScheduleGuard::class)->flight($candidate);
        $this->assertTrue(collect($issues)->contains(fn($issue)=>str_contains($issue,'overlaps flight')));
    }
}
