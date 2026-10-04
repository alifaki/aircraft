<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{CrewAbsence,CrewMember,RestPeriod,SafetyEvent};
use Illuminate\Http\Request;

class AbsenceController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.absences',['absences'=>CrewAbsence::with('crew.staff')->orderByDesc('starts_at')->paginate(30),
            'rests'=>RestPeriod::with('crew.staff')->orderByDesc('starts_at')->limit(100)->get(),
            'crew'=>CrewMember::with('staff')->orderBy('id')->get()]);
    }
    public function store(Request $request)
    {
        $this->allow('manage');
        $d=$request->validate(['crew_id'=>'required|exists:aviation_crew,id','reason'=>'required|string|max:120',
            'starts_at'=>'required|date','ends_at'=>'required|date|after:starts_at','notes'=>'nullable|string|max:2000']);
        $d['starts_at']=$this->utc($d['starts_at']); $d['ends_at']=$this->utc($d['ends_at']);
        CrewAbsence::create($d); $crew=CrewMember::findOrFail($d['crew_id']);
        foreach ($crew->duties()->whereIn('status',['published','completed'])->where('report_at','<',$d['ends_at'])->where('release_at','>',$d['starts_at'])->get() as $duty)
            SafetyEvent::create(['duty_id'=>$duty->id,'recorded_by'=>auth()->id(),'event_type'=>'availability_conflict',
                'issues'=>['Crew availability changed: review or replace the published roster.']]);
        return $this->redirectBack('Absence recorded. Review safety alerts for affected rosters.');
    }
    public function destroy(CrewAbsence $absence)
    {
        $this->allow('manage');
        if ($absence->automation_key) throw \Illuminate\Validation\ValidationException::withMessages(['absence'=>'Automatic recovery leave is retained as an audit record.']);
        $absence->delete(); return $this->redirectBack('Absence removed.');
    }
}
