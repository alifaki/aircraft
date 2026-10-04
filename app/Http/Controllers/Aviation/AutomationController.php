<?php
namespace App\Http\Controllers\Aviation;
use App\Models\Aviation\{Aircraft,CrewMember,MaintenancePlan,MaintenanceTask,PilotLeaveRule};
use App\Services\Aviation\AutomationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
class AutomationController extends AviationController
{
    public function index(AutomationService $service)
    {
        $this->allow('view');
        return view('aviation.automation',[
            'plans'=>MaintenancePlan::with('aircraft','tasks')->orderByDesc('id')->get(),
            'rules'=>PilotLeaveRule::with('crew.staff','absences')->orderByDesc('id')->get(),
            'aircraft'=>Aircraft::orderBy('registration')->get(),
            'pilots'=>CrewMember::with('staff')->where('crew_category','pilot')->orderBy('id')->get(), 'service'=>$service]);
    }
    public function sync(AutomationService $service)
    {
        $this->allow('manage'); $service->sync(); return $this->redirectBack('Maintenance schedules and pilot leave updated.');
    }
    public function maintenance(Request $request, AutomationService $service)
    {
        $this->allow('manage');
        $d=$request->validate(['aircraft_id'=>'required|exists:aviation_aircraft,id','name'=>'required|string|max:200',
            'source_reference'=>'required|string|max:255','interval_hours'=>'nullable|numeric|min:0.1|max:100000',
            'interval_cycles'=>'nullable|integer|min:1|max:1000000','interval_days'=>'nullable|integer|min:1|max:36500',
            'baseline_hours'=>'required|numeric|min:0|max:10000000','baseline_cycles'=>'required|integer|min:0|max:100000000',
            'last_completed_at'=>'required|date|before_or_equal:now',
            'warning_hours'=>'required|numeric|min:0|max:10000','warning_cycles'=>'required|integer|min:0|max:100000','warning_days'=>'required|integer|min:0|max:3650']);
        if (empty($d['interval_hours']) && empty($d['interval_cycles']) && empty($d['interval_days']))
            throw ValidationException::withMessages(['interval_hours'=>'Set at least one maintenance interval.']);
        $d['interval_minutes']=empty($d['interval_hours'])?null:(int)round($d['interval_hours']*60);
        $d['baseline_minutes']=(int)round($d['baseline_hours']*60); $d['warning_minutes']=(int)round($d['warning_hours']*60);
        $d['last_minutes']=$d['baseline_minutes']; $d['last_cycles']=$d['baseline_cycles'];
        // Baselines are the meter readings at last maintenance, not today's meter readings.
        $d['tracking_from']=$this->utc($d['last_completed_at']); $d['last_completed_at']=$d['tracking_from'];
        unset($d['interval_hours'],$d['baseline_hours'],$d['warning_hours']);
        MaintenancePlan::create($d); $service->sync(); return $this->redirectBack('Recurring maintenance plan created.');
    }
    public function book(Request $request, MaintenanceTask $task)
    {
        $this->allow('manage');
        $d=$request->validate(['scheduled_start'=>'required|date|after_or_equal:now','scheduled_end'=>'required|date|after:scheduled_start']);
        DB::transaction(function() use($d,$task) {
            Aircraft::whereKey($task->plan->aircraft_id)->lockForUpdate()->firstOrFail();
            $task=MaintenanceTask::whereKey($task->id)->lockForUpdate()->firstOrFail();
            if ($task->completed_at) throw ValidationException::withMessages(['task'=>'Completed maintenance cannot be rebooked.']);
            $start=Carbon::parse($d['scheduled_start'],'UTC'); $end=Carbon::parse($d['scheduled_end'],'UTC');
            $conflict=\App\Models\Aviation\Flight::where('aircraft_id',$task->plan->aircraft_id)->where('status','scheduled')->get()
                ->contains(fn($f)=>$f->beginning()->lt($end)&&$f->ending()->gt($start));
            if ($conflict) throw ValidationException::withMessages(['scheduled_start'=>'Unschedule overlapping flights before booking maintenance.']);
            $task->update(['scheduled_start'=>$start,'scheduled_end'=>$end]);
        });
        return $this->redirectBack('Maintenance slot booked; aircraft is unavailable in this interval.');
    }
    public function complete(Request $request, MaintenanceTask $task, AutomationService $service)
    {
        $this->allow('publish'); $d=$request->validate(['completion_reference'=>'required|string|max:2000']);
        DB::transaction(function() use($d,$task,$service) {
            Aircraft::whereKey($task->plan->aircraft_id)->lockForUpdate()->firstOrFail();
            $plan=MaintenancePlan::whereKey($task->plan_id)->lockForUpdate()->firstOrFail();
            $task=MaintenanceTask::whereKey($task->id)->lockForUpdate()->firstOrFail();
            if ($task->completed_at) throw ValidationException::withMessages(['task'=>'Maintenance has already been completed.']);
            $usage=$service->aircraftUsage($plan);
            $task->update(['status'=>'completed','completed_at'=>now('UTC'),'completion_reference'=>$d['completion_reference'],'completed_by'=>auth()->id()]);
            $plan->update(['last_completed_at'=>now('UTC'),'last_minutes'=>$usage['minutes'],'last_cycles'=>$usage['cycles']]);
        });
        $service->sync(); return $this->redirectBack('Maintenance completed. The next interval has been calculated.');
    }
    public function leaveRule(Request $request, AutomationService $service)
    {
        $this->allow('policy');
        $d=$request->validate(['crew_id'=>['required',Rule::exists('aviation_crew','id')->where('crew_category','pilot'),'unique:aviation_pilot_leave_rules,crew_id'],
            'source_reference'=>'required|string|max:255','threshold_hours'=>'required|numeric|min:0.1|max:100000',
            'leave_days'=>'required|integer|min:1|max:365','baseline_hours'=>'required|numeric|min:0|max:100000',
            'tracking_from'=>'required|date|before_or_equal:now']);
        $d['threshold_minutes']=(int)round($d['threshold_hours']*60); $d['baseline_minutes']=(int)round($d['baseline_hours']*60);
        $d['tracking_from']=$this->utc($d['tracking_from']);unset($d['threshold_hours'],$d['baseline_hours']);
        PilotLeaveRule::create($d); $service->sync(); return $this->redirectBack('Pilot flight-hour leave rule activated.');
    }
    public function updateLeaveRule(Request $request, PilotLeaveRule $rule, AutomationService $service)
    {
        $this->allow('policy');
        $d=$request->validate(['source_reference'=>'required|string|max:255',
            'threshold_hours'=>'required|numeric|min:0.1|max:100000','leave_days'=>'required|integer|min:1|max:365',
            'active'=>'required|boolean']);
        $d['threshold_minutes']=(int)round($d['threshold_hours']*60); unset($d['threshold_hours']);
        DB::transaction(function() use($rule,$d) {
            CrewMember::whereKey($rule->crew_id)->lockForUpdate()->firstOrFail();
            PilotLeaveRule::whereKey($rule->id)->lockForUpdate()->firstOrFail()->update($d);
        });
        $service->sync(); return $this->redirectBack('Leave rule updated; existing recovery leave remains recorded.');
    }
    public function updateMaintenance(Request $request, MaintenancePlan $plan, AutomationService $service)
    {
        $this->allow('policy');
        $d=$request->validate(['active'=>'required|boolean','source_reference'=>'required|string|max:255',
            'interval_hours'=>'nullable|numeric|min:0.1|max:100000','interval_cycles'=>'nullable|integer|min:1|max:1000000',
            'interval_days'=>'nullable|integer|min:1|max:36500']);
        if (empty($d['interval_hours']) && empty($d['interval_cycles']) && empty($d['interval_days']))
            throw ValidationException::withMessages(['interval_hours'=>'Set at least one maintenance interval.']);
        $d['interval_minutes']=empty($d['interval_hours'])?null:(int)round($d['interval_hours']*60); unset($d['interval_hours']);
        DB::transaction(function() use($plan,$d) {
            Aircraft::whereKey($plan->aircraft_id)->lockForUpdate()->firstOrFail();
            MaintenancePlan::whereKey($plan->id)->lockForUpdate()->firstOrFail()->update($d);
        });
        $service->sync(); return $this->redirectBack('Maintenance programme updated; meter readings and completion history retained.');
    }

}
