<?php
namespace Tests\Feature;
use App\Models\{Branch,Company,Staff};
use App\Models\Aviation\{Aircraft,AircraftType,Airport,CrewMember,Duty,FatiguePolicy,Flight,Qualification,RestPeriod,MaintenancePlan,MaintenanceTask,PilotLeaveRule,CrewAbsence,SafetyEvent};
use App\Services\Aviation\{AutomationService,ScheduleGuard};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
class AviationAutomationTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp(); Carbon::setTestNow(Carbon::parse('2026-10-04 12:00:00','UTC'));
    }
    protected function tearDown(): void
    {
        Carbon::setTestNow(); parent::tearDown();
    }
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


    private function plan(array $data, array $overrides=[]): MaintenancePlan
    {
        return MaintenancePlan::create($overrides+['aircraft_id'=>$data['plane']->id,'name'=>'100-hour inspection',
            'source_reference'=>'TEST-MAINT-01','interval_minutes'=>6000,'baseline_minutes'=>12000,'baseline_cycles'=>100,
            'last_minutes'=>12000,'last_cycles'=>100,'last_completed_at'=>now()->subDays(10),'tracking_from'=>now()->subDays(10),
            'warning_minutes'=>600,'warning_cycles'=>10,'warning_days'=>7,'active'=>true]);
    }
    private function actual(array $data, int $hours=2, bool $withPilot=false): Flight
    {
        $duty=null;
        if ($withPilot) {
            $duty=Duty::create(['reference'=>'ACT-'.Flight::count(),'type'=>'flight','report_at'=>now()->subHours($hours+2),
                'release_at'=>now()->subMinutes(30),'status'=>'completed']);
            $duty->crew()->attach($data['pilot']->id,['role'=>'captain']);
        }
        return Flight::create(['flight_number'=>'ACT'.Flight::count(),'flight_date'=>now()->toDateString(),'leg_sequence'=>1,
            'aircraft_id'=>$data['plane']->id,'origin_id'=>$data['a']->id,'destination_id'=>$data['b']->id,'duty_id'=>$duty?->id,
            'departure_at'=>now()->subHours($hours+1),'arrival_at'=>now()->subHour(),
            'actual_off_at'=>now()->subHours($hours+1),'actual_on_at'=>now()->subHour(),
            'planned_landings'=>1,'actual_landings'=>1,'status'=>'operated']);
    }
    private function rule(array $data, array $overrides=[]): PilotLeaveRule
    {
        return PilotLeaveRule::create($overrides+['crew_id'=>$data['pilot']->id,'source_reference'=>'TEST-LEAVE-01',
            'threshold_minutes'=>120,'leave_days'=>2,'baseline_minutes'=>0,'tracking_from'=>now()->subDay(),'active'=>true]);
    }
    public function test_actual_usage_is_added_to_the_maintenance_baseline(): void
    {
        $data=$this->data();$plan=$this->plan($data);$this->actual($data);
        $usage=app(AutomationService::class)->aircraftUsage($plan);
        $this->assertSame(['minutes'=>12120,'cycles'=>101],$usage);
    }
    public function test_calendar_and_cycle_limits_trigger_due_tasks_without_duplicates(): void
    {
        $data=$this->data();$plan=$this->plan($data,['interval_days'=>5]);
        $service=app(AutomationService::class);$service->sync();$service->sync();
        $this->assertSame(1,MaintenanceTask::count());$this->assertSame('due',$plan->tasks()->first()->status);
        $plan->update(['interval_days'=>null,'interval_cycles'=>1]);$this->actual($data);$service->sync();
        $this->assertSame('due',$plan->tasks()->first()->status);
    }
    public function test_projected_flight_is_blocked_when_maintenance_limit_is_reached(): void
    {
        $data=$this->data();$this->plan($data,['interval_minutes'=>60]);
        $flight=new Flight(['aircraft_id'=>$data['plane']->id,'departure_at'=>now()->addHour(),'arrival_at'=>now()->addHours(2),'planned_landings'=>1]);
        $this->assertNotEmpty(app(AutomationService::class)->maintenanceIssues($flight));
    }
    public function test_maintenance_booking_blocks_overlapping_flights(): void
    {
        $data=$this->data();$plan=$this->plan($data);$service=app(AutomationService::class);$service->sync();
        $plan->tasks()->first()->update(['scheduled_start'=>now()->addHour(),'scheduled_end'=>now()->addHours(3)]);
        $flight=new Flight(['aircraft_id'=>$data['plane']->id,'departure_at'=>now()->addHour(),'arrival_at'=>now()->addHours(2),'planned_landings'=>1]);
        $this->assertContains('Aircraft overlaps booked maintenance: '.$plan->name,$service->maintenanceIssues($flight));
    }
    public function test_completing_a_maintenance_cycle_resets_the_next_usage_limit(): void
    {
        $data=$this->data();$plan=$this->plan($data,['interval_minutes'=>120]);$this->actual($data);
        $service=app(AutomationService::class);$service->sync();$usage=$service->aircraftUsage($plan);
        $plan->tasks()->first()->update(['status'=>'completed','completed_at'=>now(),'completion_reference'=>'WO-1']);
        $plan->update(['last_minutes'=>$usage['minutes'],'last_cycles'=>$usage['cycles'],'last_completed_at'=>now()]);
        $service->sync();$this->assertSame(120,$service->maintenanceState($plan)['remaining_minutes']);
        $this->assertSame(2,$plan->tasks()->count());
    }
    public function test_leave_is_created_at_threshold_once_and_blocks_crew_availability(): void
    {
        $data=$this->data();$rule=$this->rule($data);$this->actual($data,2,true);
        $service=app(AutomationService::class);$service->sync();$service->sync();
        $this->assertSame(1,CrewAbsence::where('leave_rule_id',$rule->id)->count());
        $absence=$rule->absences()->first();$this->assertEquals(now(),$absence->starts_at);
        $this->assertEquals(now()->addDays(2),$absence->ends_at);
    }
    public function test_hour_counter_resets_only_after_leave_ends(): void
    {
        $data=$this->data();$rule=$this->rule($data);$this->actual($data,2,true);$service=app(AutomationService::class);
        $service->sync();$this->assertSame(120,$service->pilotMinutes($rule));
        Carbon::setTestNow(now()->addDays(3));$service->sync();
        $this->assertSame(0,$service->pilotMinutes($rule->fresh()));$this->assertSame(1,$rule->absences()->count());
    }
    public function test_projected_hours_cannot_exceed_leave_threshold(): void
    {
        $data=$this->data();$this->rule($data,['threshold_minutes'=>60]);
        $flight=new Flight(['departure_at'=>now()->addHour(),'arrival_at'=>now()->addHours(3)]);
        $issues=app(AutomationService::class)->pilotLeaveIssues($data['pilot'],collect([$flight]),now()->addHours(4));
        $this->assertNotEmpty($issues);
    }
    public function test_cancelled_flights_do_not_count_toward_maintenance_or_leave(): void
    {
        $data=$this->data();$plan=$this->plan($data);$rule=$this->rule($data);$f=$this->actual($data,2,true);$f->update(['status'=>'cancelled']);
        $service=app(AutomationService::class);$this->assertSame(12000,$service->aircraftUsage($plan)['minutes']);
        $this->assertSame(0,$service->pilotMinutes($rule));$service->sync();$this->assertSame(0,$rule->absences()->count());
    }
    public function test_leave_flags_published_duties_that_need_a_replacement_pilot(): void
    {
        $data=$this->data();$this->rule($data);$this->actual($data,2,true);
        $duty=Duty::create(['reference'=>'NEXT','type'=>'flight','report_at'=>now()->addHours(2),'release_at'=>now()->addHours(4),'status'=>'published']);
        $duty->crew()->attach($data['pilot']->id,['role'=>'captain']);
        $service=app(AutomationService::class);$service->sync();$service->sync();
        $this->assertSame(1,SafetyEvent::where('event_type','automatic_leave_conflict')->count());
    }
    private function admin(array $data): \App\Models\User
    {
        config(['app.key'=>'base64:'.base64_encode(str_repeat('a',32))]);
        $role=\App\Models\Role::create(['name'=>'Administrator','status'=>'active']);
        return \App\Models\User::create(['username'=>'testadmin','password'=>bcrypt('test-only-password'),
            'staff_id'=>$data['pilot']->staff_id,'role_id'=>$role->id,'status'=>'active','type'=>'staff']);
    }
    public function test_ajax_forms_return_json_instead_of_a_redirect(): void
    {
        $data=$this->data();$this->actingAs($this->admin($data));
        $this->postJson(route('aviation.airports.store'),['icao'=>'HTZZ','iata'=>'ZZZ','name'=>'New Airport',
            'city'=>'Test','country'=>'Tanzania','timezone'=>'Africa/Dar_es_Salaam','active'=>1])
            ->assertOk()->assertJson(['message'=>'Airport saved.','level'=>'success']);
        $this->assertDatabaseHas('aviation_airports',['icao'=>'HTZZ']);
    }
    public function test_ajax_business_rule_failures_return_422_without_redirecting(): void
    {
        $data=$this->data();$this->actingAs($this->admin($data));$f=$this->actual($data);
        $this->postJson(route('aviation.flights.schedule',$f))->assertStatus(422)->assertJsonValidationErrors('flight');
    }
    public function test_ajax_validation_preserves_specific_field_errors(): void
    {
        $data=$this->data();$this->actingAs($this->admin($data));
        $this->postJson(route('aviation.airports.store'),['timezone'=>'bad-zone'])
            ->assertStatus(422)->assertJsonValidationErrors(['icao','timezone']);
    }
    public function test_management_pages_render_and_timezone_is_a_select(): void
    {
        $data=$this->data();$this->actingAs($this->admin($data));
        $this->get(route('aviation.airports.index'))->assertOk()->assertSee('Choose time zone')->assertSee('Africa/Dar_es_Salaam');
        $this->get(route('aviation.automation.index'))->assertOk()->assertSee('Maintenance &amp; pilot leave',false);
        $this->get(route('aviation.dashboard'))->assertOk();
        $this->get(route('aviation.flights.index',['from'=>'2026-10-01','to'=>'2026-10-07']))->assertOk();
    }
    public function test_automatic_leave_cannot_be_deleted(): void
    {
        $data=$this->data();$this->actingAs($this->admin($data));$rule=$this->rule($data);$this->actual($data,2,true);
        app(AutomationService::class)->sync();$absence=$rule->absences()->first();
        $this->deleteJson(route('aviation.absences.destroy',$absence))->assertStatus(422);
        $this->assertDatabaseHas('aviation_crew_absences',['id'=>$absence->id]);
    }
    public function test_maintenance_can_be_completed_through_ajax_and_recalculates_next_cycle(): void
    {
        $data=$this->data();$user=$this->admin($data);$this->actingAs($user);
        $plan=$this->plan($data,['interval_minutes'=>120]);$this->actual($data);app(AutomationService::class)->sync();
        $task=$plan->tasks()->first();
        $this->postJson(route('aviation.automation.complete',$task),['completion_reference'=>'WO-123'])->assertOk();
        $this->assertDatabaseHas('aviation_maintenance_tasks',['id'=>$task->id,'status'=>'completed','completed_by'=>$user->id]);
        $this->assertSame(120,app(AutomationService::class)->maintenanceState($plan->fresh())['remaining_minutes']);
    }
    public function test_users_without_aviation_permission_cannot_modify_rules(): void
    {
        $data=$this->data();$user=$this->admin($data);$role=$user->role;$role->update(['name'=>'Restricted']);
        $this->actingAs($user->fresh());$this->postJson(route('aviation.automation.sync'))->assertForbidden();
    }

    public function test_future_flights_can_be_rostered_after_already_scheduled_recovery_leave(): void
    {
        $data=$this->data();$rule=$this->rule($data);$this->actual($data,2,true);$service=app(AutomationService::class);$service->sync();
        $flight=new Flight(['departure_at'=>now()->addDays(3),'arrival_at'=>now()->addDays(3)->addHour()]);
        $this->assertSame([],$service->pilotLeaveIssues($data['pilot'],collect([$flight]),$flight->arrival_at));
    }

    public function test_inserting_an_earlier_flight_cannot_invalidate_later_maintenance_limits(): void
    {
        $data=$this->data();$this->plan($data,['interval_minutes'=>90]);
        Flight::create(['flight_number'=>'LATER','flight_date'=>now()->toDateString(),'leg_sequence'=>1,
            'aircraft_id'=>$data['plane']->id,'origin_id'=>$data['a']->id,'destination_id'=>$data['b']->id,
            'departure_at'=>now()->addHours(4),'arrival_at'=>now()->addHours(5),'planned_landings'=>1,'status'=>'scheduled']);
        $early=new Flight(['aircraft_id'=>$data['plane']->id,'departure_at'=>now()->addHour(),'arrival_at'=>now()->addHours(2),'planned_landings'=>1]);
        $this->assertNotEmpty(app(AutomationService::class)->maintenanceIssues($early));
    }

}
