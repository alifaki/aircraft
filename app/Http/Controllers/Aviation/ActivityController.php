<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{Duty,DutyActivity};
use Illuminate\Http\Request;

class ActivityController extends AviationController
{
    public function store(Request $request, Duty $duty)
    {
        $this->allow('manage');
        if ($duty->status!=='draft') return back()->withErrors(['activity'=>'Unpublish before editing activity.']);
        $d=$request->validate(['type'=>'required|in:standby,positioning,deadheading,split_duty,training,preflight,postflight,other',
            'starts_at'=>'required|date','ends_at'=>'required|date|after:starts_at','notes'=>'nullable|string|max:2000']);
        $d['starts_at']=$this->utc($d['starts_at']); $d['ends_at']=$this->utc($d['ends_at']);
        if ($d['starts_at']<$duty->report_at->format('Y-m-d H:i:s') || $d['ends_at']>$duty->release_at->format('Y-m-d H:i:s'))
            return back()->withErrors(['activity'=>'Activity must fit inside the duty report and release times.']);
        $duty->activities()->create($d); return $this->redirectBack('Duty activity recorded.');
    }
    public function destroy(Duty $duty, DutyActivity $activity)
    {
        $this->allow('manage');
        if ($duty->status!=='draft' || $activity->duty_id!==$duty->id) abort(403);
        $activity->delete(); return $this->redirectBack('Activity removed.');
    }
}
