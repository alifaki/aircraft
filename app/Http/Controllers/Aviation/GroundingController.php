<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{Grounding,Aircraft,Flight,SafetyEvent};
use Illuminate\Http\Request;

class GroundingController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.groundings',['groundings'=>Grounding::with('aircraft')->orderByDesc('starts_at')->paginate(30),
            'aircraft'=>Aircraft::orderBy('registration')->get()]);
    }
    public function store(Request $request)
    {
        $this->allow('manage');
        $d=$request->validate(['aircraft_id'=>'required|exists:aviation_aircraft,id','reason'=>'required|string|max:120',
            'starts_at'=>'required|date','ends_at'=>'required|date|after:starts_at','notes'=>'nullable|string|max:2000']);
        $d['starts_at']=$this->utc($d['starts_at']); $d['ends_at']=$this->utc($d['ends_at']);
        Grounding::create($d);
        foreach (Flight::where('aircraft_id',$d['aircraft_id'])->whereIn('status',['scheduled','operated'])
            ->where('departure_at','<',$d['ends_at'])->where('arrival_at','>',$d['starts_at'])->get() as $flight)
            SafetyEvent::create(['flight_id'=>$flight->id,'duty_id'=>$flight->duty_id,'recorded_by'=>auth()->id(),
                'event_type'=>'grounding_conflict','issues'=>['Grounding overlaps this scheduled flight: reassign or cancel it.']]);
        return $this->redirectBack('Grounding recorded. Review safety alerts for affected flights.');
    }
    public function destroy(Grounding $grounding)
    {
        $this->allow('manage'); $grounding->delete(); return $this->redirectBack('Grounding removed.');
    }
}
