<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{Aircraft,CrewMember,Duty,Flight,FatiguePolicy,SafetyEvent};
use App\Services\Aviation\ScheduleGuard;

class DashboardController extends AviationController
{
    public function index(ScheduleGuard $guard)
    {
        $this->allow('view');
        $start=now('UTC')->startOfDay(); $end=$start->copy()->addDays(7);
        $upcoming=Flight::with('aircraft','origin','destination','duty')->where('departure_at','>=',$start)
            ->where('departure_at','<',$end)->where('status','!=','cancelled')->orderBy('departure_at')->limit(12)->get();
        $alerts=[];
        foreach (Duty::with('crew.staff','flights.aircraft.type','pilotPolicy','cabinPolicy')
            ->where('status','published')->where('report_at','>=',$start)->where('report_at','<',$end)->limit(15)->get() as $duty) {
            $issues=$guard->duty($duty);
            if ($issues) $alerts[]=['reference'=>$duty->reference,'issues'=>$issues];
        }
        return view('aviation.dashboard',['upcoming'=>$upcoming,'alerts'=>$alerts,
            'maintenanceDue'=>\App\Models\Aviation\MaintenanceTask::whereNull('completed_at')->where('status','due')->whereHas('plan',fn($q)=>$q->where('active',true))->count(),
            'automaticLeave'=>\App\Models\Aviation\CrewAbsence::whereNotNull('automation_key')->where('ends_at','>',now('UTC'))->count(),
            'fleetCount'=>Aircraft::where('status','active')->count(),
            'crewCount'=>CrewMember::where('status','active')->count(),
            'upcomingCount'=>Flight::where('departure_at','>=',$start)->where('departure_at','<',$end)->whereIn('status',['scheduled','draft'])->count(),
            'draftDuties'=>Duty::where('status','draft')->count(),
            'approvedPolicies'=>FatiguePolicy::where('status','approved')->count(),
            'recentEvents'=>SafetyEvent::latest()->limit(5)->get()]);
    }
}
