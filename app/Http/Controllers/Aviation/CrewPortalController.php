<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{CrewMember,CrewAbsence,SafetyEvent};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrewPortalController extends AviationController
{
    private function ownCrew(): ?CrewMember
    {
        return CrewMember::where('staff_id',auth()->user()->staff_id)->first();
    }
    public function index()
    {
        $crew=$this->ownCrew();
        return view('aviation.my-roster',['crew'=>$crew,
            'duties'=>$crew?->duties()->with('flights.origin','flights.destination','activities')
                ->whereIn('status',['published','completed'])->where('release_at','>=',now('UTC')->subDays(14))
                ->orderBy('report_at')->limit(50)->get() ?? collect()]);
    }
    public function report(Request $request)
    {
        $crew=$this->ownCrew(); abort_unless($crew,403);
        $data=$request->validate(['duty_id'=>'nullable|exists:aviation_duties,id',
            'details'=>'required|string|min:20|max:3000','unfit_for_duty'=>'nullable|boolean',
            'unfit_until'=>'required_if:unfit_for_duty,1|nullable|date|after:now']);
        if (!empty($data['duty_id']) && !$crew->duties()->whereKey($data['duty_id'])->exists()) abort(403);
        DB::transaction(function() use ($crew,$data) {
            $dutyId=$data['duty_id'] ?? null;
            SafetyEvent::create(['duty_id'=>$dutyId,'recorded_by'=>auth()->id(),
                'event_type'=>!empty($data['unfit_for_duty'])?'crew_unfit_report':'crew_fatigue_report',
                'issues'=>[$data['details']]]);
            if (!empty($data['unfit_for_duty'])) {
                $startsAt=now('UTC'); $endsAt=$this->utc($data['unfit_until']);
                CrewAbsence::create(['crew_id'=>$crew->id,'reason'=>'Crew reported unfit for duty',
                    'starts_at'=>$startsAt,'ends_at'=>$endsAt,'notes'=>'See safety event log.']);
                foreach ($crew->duties()->where('status','published')->where('report_at','<',$endsAt)
                    ->where('release_at','>',$startsAt)->get() as $affected) {
                    SafetyEvent::create(['duty_id'=>$affected->id,'recorded_by'=>auth()->id(),
                        'event_type'=>'crew_unfit_roster_conflict',
                        'issues'=>['A rostered crew member reported unfit: review or replace this published duty.']]);
                }
            }
        });
        return $this->redirectBack('Your report has been recorded for operations review.');
    }
}
