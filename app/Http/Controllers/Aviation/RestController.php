<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{CrewMember,RestPeriod};
use Illuminate\Http\Request;

class RestController extends AviationController
{
    public function store(Request $request)
    {
        $this->allow('manage');
        $data=$request->validate(['crew_id'=>'required|exists:aviation_crew,id','starts_at'=>'required|date',
            'ends_at'=>'required|date|after:starts_at','status'=>'required|in:planned,confirmed','notes'=>'nullable|string|max:2000']);
        $data['starts_at']=$this->utc($data['starts_at']); $data['ends_at']=$this->utc($data['ends_at']);
        if ($data['status']==='confirmed' && \Carbon\Carbon::parse($data['ends_at'],'UTC')->isFuture())
            return back()->withErrors(['rest'=>'Future rest can be planned, but not confirmed.']);
        $crew=CrewMember::findOrFail($data['crew_id']);
        if ($crew->duties()->whereIn('status',['published','completed'])->where('report_at','<',$data['ends_at'])
            ->where('release_at','>',$data['starts_at'])->exists())
            return back()->withErrors(['rest'=>'A rest interval cannot overlap a published duty.']);
        $data['recorded_by']=auth()->id(); RestPeriod::create($data);
        return $this->redirectBack('Rest period recorded. Completed rest must be confirmed.');
    }
    public function confirm(RestPeriod $rest)
    {
        $this->allow('publish');
        if ($rest->ends_at->isFuture()) return back()->withErrors(['rest'=>'Confirm rest only after its planned end.']);
        $rest->update(['status'=>'confirmed','recorded_by'=>auth()->id()]);
        return $this->redirectBack('Rest period confirmed.');
    }
    public function destroy(RestPeriod $rest)
    {
        $this->allow('manage'); $rest->delete(); return $this->redirectBack('Rest record removed; recheck affected rosters.');
    }
}
