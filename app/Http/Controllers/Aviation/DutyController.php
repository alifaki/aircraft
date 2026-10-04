<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{Duty,CrewMember,FatiguePolicy,Flight,Aircraft,SafetyEvent};
use App\Services\Aviation\ScheduleGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DutyController extends AviationController
{
    public function index(Request $request)
    {
        $this->allow('view');
        $request->validate(['from'=>'nullable|date','to'=>'nullable|date|after_or_equal:from']);
        $from=$request->input('from',now('UTC')->toDateString()); $to=$request->input('to',now('UTC')->addDays(7)->toDateString());
        return view('aviation.duties',['duties'=>Duty::with('crew.staff','flights.origin','flights.destination','pilotPolicy','cabinPolicy','activities')
            ->whereDate('report_at','>=',$from)->whereDate('report_at','<=',$to)->orderBy('report_at')->paginate(25)->withQueryString(),
            'crew'=>CrewMember::with('staff')->where('status','active')->get(),
            'policies'=>FatiguePolicy::where('status','approved')->orderByDesc('id')->get(),
            'flights'=>Flight::with('origin','destination')->where('status','!=','operated')
                ->where(function($q){$q->whereNull('duty_id')->orWhereHas('duty',fn($d)=>$d->where('status','draft'));})
                ->orderBy('departure_at')->limit(200)->get(), 'from'=>$from,'to'=>$to]);
    }
    private function fields(Request $request, ?Duty $duty=null): array
    {
        $d=$request->validate(['reference'=>'required|string|max:32|unique:aviation_duties,reference,'.($duty?->id ?? 'NULL').',id',
            'type'=>'required|in:flight,standby,positioning,deadheading,training,split_duty,other',
            'report_at'=>'required|date','release_at'=>'required|date|after:report_at',
            'pilot_policy_id'=>'nullable|exists:aviation_fatigue_policies,id',
            'cabin_policy_id'=>'nullable|exists:aviation_fatigue_policies,id','notes'=>'nullable|string|max:2000']);
        $d['report_at']=$this->utc($d['report_at']); $d['release_at']=$this->utc($d['release_at']);
        $d['reference']=strtoupper(trim($d['reference']));
        foreach (['pilot_policy_id'=>'pilot','cabin_policy_id'=>'cabin'] as $key=>$category) {
            if (!empty($d[$key]) && FatiguePolicy::findOrFail($d[$key])->crew_category!==$category)
                throw \Illuminate\Validation\ValidationException::withMessages([$key=>'Select a '.$category.' policy.']);
        }
        return $d;
    }
    public function store(Request $request)
    {
        $this->allow('manage'); Duty::create($this->fields($request)+['status'=>'draft']);
        return $this->redirectBack('Duty drafted. Assign crew and flights before publication.');
    }
    public function update(Request $request, Duty $duty)
    {
        $this->allow('manage');
        if ($duty->status!=='draft') return back()->withErrors(['duty'=>'Only draft duties may be edited.']);
        $duty->update($this->fields($request,$duty)); return $this->redirectBack('Duty updated.');
    }
    public function assign(Request $request, Duty $duty)
    {
        $this->allow('manage');
        if ($duty->status!=='draft') return back()->withErrors(['duty'=>'Unpublish before changing assignments.']);
        $d=$request->validate(['crew_id'=>'required|exists:aviation_crew,id','role'=>'required|in:captain,first_officer,cabin_crew']);
        $crew=CrewMember::findOrFail($d['crew_id']);
        if (($crew->crew_category==='pilot') !== in_array($d['role'],['captain','first_officer'],true))
            return back()->withErrors(['role'=>'Role does not match the crew category.']);
        $duty->crew()->syncWithoutDetaching([$crew->id=>['role'=>$d['role']]]);
        return $this->redirectBack('Crew assigned.');
    }
    public function removeCrew(Duty $duty, CrewMember $crew)
    {
        $this->allow('manage');
        if ($duty->status!=='draft') return back()->withErrors(['duty'=>'Unpublish before changing assignments.']);
        $duty->crew()->detach($crew->id); return $this->redirectBack('Crew removed from duty.');
    }
    public function attachFlight(Request $request, Duty $duty)
    {
        $this->allow('manage');
        $d=$request->validate(['flight_id'=>'required|exists:aviation_flights,id']);
        if ($duty->status!=='draft' || $duty->type!=='flight') return back()->withErrors(['duty'=>'Only draft flight duties accept legs.']);
        $flight=Flight::findOrFail($d['flight_id']);
        if ($flight->status==='operated' || ($flight->duty_id && $flight->duty_id!==$duty->id))
            return back()->withErrors(['flight'=>'Flight already belongs to another duty or has operated.']);
        $flight->update(['duty_id'=>$duty->id]); return $this->redirectBack('Flight added to duty.');
    }
    public function detachFlight(Duty $duty, Flight $flight)
    {
        $this->allow('manage');
        if ($duty->status!=='draft' || $flight->duty_id!==$duty->id) return back()->withErrors(['duty'=>'Only draft duty flights can be removed.']);
        $flight->update(['duty_id'=>null]); return $this->redirectBack('Flight detached.');
    }
    public function publish(Duty $duty, ScheduleGuard $guard)
    {
        $this->allow('publish');
        return DB::transaction(function() use ($duty,$guard) {
            $duty=Duty::whereKey($duty->id)->lockForUpdate()->firstOrFail();
            if ($duty->status!=='draft') return back()->withErrors(['duty'=>'Only draft duties can be published.']);
            $ids=$duty->crew()->pluck('aviation_crew.id')->sort()->values()->all();
            CrewMember::whereIn('id',$ids)->orderBy('id')->lockForUpdate()->get();
            Aircraft::whereIn('id',$duty->flights()->pluck('aircraft_id'))->orderBy('id')->lockForUpdate()->get();
            $issues=$guard->duty($duty->fresh());
            if ($issues) return back()->withErrors(['safety'=>$issues]);
            $duty->flights()->where('status','draft')->update(['status'=>'scheduled']);
            $duty->update(['status'=>'published','published_at'=>now(),'published_by'=>auth()->id()]);
            return $this->redirectBack('Duty published after safety checks.');
        });
    }
    public function unpublish(Duty $duty)
    {
        $this->allow('publish');
        if ($duty->status!=='published' || $duty->flights()->whereNotNull('actual_off_at')->exists())
            return back()->withErrors(['duty'=>'Actual times recorded. Complete the duty and log exceptions instead of unpublishing.']);
        $duty->update(['status'=>'draft','published_at'=>null,'published_by'=>null]);
        return $this->redirectBack('Duty returned to draft.');
    }
    public function complete(Request $request, Duty $duty, ScheduleGuard $guard)
    {
        $this->allow('publish');
        $data=$request->validate(['actual_report_at'=>'required|date','actual_release_at'=>'required|date|after:actual_report_at|before_or_equal:now']);
        if ($duty->status!=='published') return back()->withErrors(['duty'=>'Publish this duty before recording completion.']);
        if ($duty->flights()->where(function($q){$q->whereNull('actual_off_at')->orWhereNull('actual_on_at');})->exists())
            return back()->withErrors(['duty'=>'Record actual off/on times for every flight first.']);
        $duty->update(['actual_report_at'=>$this->utc($data['actual_report_at']),
            'actual_release_at'=>$this->utc($data['actual_release_at'])]);
        $issues=$guard->duty($duty->fresh());
        if ($issues) SafetyEvent::create(['duty_id'=>$duty->id,'recorded_by'=>auth()->id(),
            'event_type'=>'completed_duty_exception','issues'=>$issues]);
        $duty->flights()->where('status','!=','cancelled')->update(['status'=>'operated']); $duty->update(['status'=>'completed']);
        app(\App\Services\Aviation\AutomationService::class)->sync();
        return $issues ? back()->with('aviation_warning','Duty completed; '.count($issues).' safety exception(s) logged for follow-up.') : $this->redirectBack('Actual duty completed and checked.');
    }
    public function destroy(Duty $duty)
    {
        $this->allow('manage');
        if ($duty->status!=='draft') return back()->withErrors(['duty'=>'Only draft duties can be deleted.']);
        $duty->flights()->update(['duty_id'=>null]); $duty->delete(); return $this->redirectBack('Draft duty removed.');
    }
}
