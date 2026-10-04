<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{Flight,Aircraft,Airport,Duty,SafetyEvent};
use App\Services\Aviation\ScheduleGuard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FlightController extends AviationController
{
    public function index(Request $request)
    {
        $this->allow('view');
        $request->validate(['from'=>'nullable|date','to'=>'nullable|date|after_or_equal:from','aircraft_id'=>'nullable|exists:aviation_aircraft,id']);
        $from=$request->input('from', now('UTC')->toDateString());
        $to=$request->input('to', now('UTC')->addDays(7)->toDateString());
        return view('aviation.flights',['flights'=>Flight::with('aircraft.type','origin','destination','duty')
            ->whereDate('departure_at','>=',$from)->whereDate('departure_at','<=',$to)
            ->when($request->aircraft_id,fn($q)=>$q->where('aircraft_id',$request->aircraft_id))
            ->orderBy('departure_at')->paginate(30)->withQueryString(),
            'aircraft'=>Aircraft::with('type')->orderBy('registration')->get(),
            'airports'=>Airport::where('active',true)->orderBy('icao')->get(),
            'duties'=>Duty::where('type','flight')->where('status','draft')->orderByDesc('id')->limit(200)->get(),
            'from'=>$from,'to'=>$to]);
    }
    private function fields(Request $request, ?Flight $flight=null): array
    {
        $d=$request->validate(['flight_number'=>'required|string|max:32','leg_sequence'=>'required|integer|min:1|max:99','aircraft_id'=>'required|exists:aviation_aircraft,id',
            'origin_id'=>'required|exists:aviation_airports,id','destination_id'=>'required|different:origin_id|exists:aviation_airports,id',
            'duty_id'=>'nullable|exists:aviation_duties,id','departure_at'=>'required|date','arrival_at'=>'required|date|after:departure_at',
            'planned_landings'=>'required|integer|min:1|max:20',
            'notes'=>'nullable|string|max:2000']);
        $d['departure_at']=$this->utc($d['departure_at']); $d['arrival_at']=$this->utc($d['arrival_at']);
        $d['flight_date']=Carbon::parse($d['departure_at'],'UTC')->toDateString();
        $d['flight_number']=strtoupper(trim($d['flight_number']));
        if (Flight::where('flight_number',$d['flight_number'])->where('flight_date',$d['flight_date'])->where('leg_sequence',$d['leg_sequence'])
            ->when($flight,fn($q)=>$q->where('id','!=',$flight->id))->exists())
            throw \Illuminate\Validation\ValidationException::withMessages(['flight_number'=>'This flight number and leg sequence already exist on that UTC date.']);
        if (!empty($d['duty_id']) && ($duty=Duty::find($d['duty_id'])) && ($duty->status!=='draft' || $duty->type!=='flight'))
            throw \Illuminate\Validation\ValidationException::withMessages(['duty_id'=>'Only a draft flight duty can receive a flight.']);
        return $d;
    }
    public function store(Request $request)
    {
        $this->allow('manage'); Flight::create($this->fields($request)+['status'=>'draft']);
        return $this->redirectBack('Flight leg drafted. Schedule it after aircraft checks.');
    }
    public function update(Request $request, Flight $flight)
    {
        $this->allow('manage');
        if ($flight->status!=='draft' || $flight->duty?->status==='published')
            return back()->withErrors(['flight'=>'Unschedule this flight and unpublish its duty before editing.']);
        $flight->update($this->fields($request,$flight)); return $this->redirectBack('Draft flight updated.');
    }
    public function schedule(Flight $flight, ScheduleGuard $guard)
    {
        $this->allow('publish');
        return DB::transaction(function () use ($flight,$guard) {
            Aircraft::whereKey($flight->aircraft_id)->lockForUpdate()->firstOrFail();
            $flight->refresh();
            if ($flight->status!=='draft') return back()->withErrors(['flight'=>'Only draft flights can be scheduled.']);
            $issues=$guard->flight($flight);
            if ($issues) return back()->withErrors(['safety'=>$issues]);
            $flight->update(['status'=>'scheduled']);
            return $this->redirectBack('Aircraft and flight scheduled. Assign a flight duty to publish its crew roster.');
        });
    }
    public function unschedule(Flight $flight)
    {
        $this->allow('publish');
        if ($flight->status!=='scheduled' || $flight->duty?->status==='published')
            return back()->withErrors(['flight'=>'Unpublish the duty before unscheduling this flight.']);
        $flight->update(['status'=>'draft']); return $this->redirectBack('Flight returned to draft.');
    }
    public function actual(Request $request, Flight $flight, ScheduleGuard $guard)
    {
        $this->allow('publish');
        $d=$request->validate(['actual_off_at'=>'required|date','actual_on_at'=>'required|date|after:actual_off_at|before_or_equal:now',
            'actual_landings'=>'required|integer|min:1|max:20']);
        if ($flight->status!=='scheduled' || !$flight->duty || $flight->duty->status!=='published')
            return back()->withErrors(['flight'=>'Publish the flight and crew duty before recording actual block times.']);
        $flight->update(['actual_off_at'=>$this->utc($d['actual_off_at']),'actual_on_at'=>$this->utc($d['actual_on_at']),
            'actual_landings'=>$d['actual_landings']]);
        app(\App\Services\Aviation\AutomationService::class)->sync();
        $issues=$guard->duty($flight->duty->fresh());
        if ($issues) SafetyEvent::create(['flight_id'=>$flight->id,'duty_id'=>$flight->duty_id,
            'recorded_by'=>auth()->id(),'event_type'=>'actual_time_exception','issues'=>$issues]);
        return $issues ? back()->with('aviation_warning','Actual times recorded with '.count($issues).' safety exception(s). Review the event log.') : $this->redirectBack('Actual block times recorded.');
    }
    public function destroy(Flight $flight)
    {
        $this->allow('manage');
        if ($flight->status!=='draft') return back()->withErrors(['flight'=>'Unschedule before deleting.']);
        $flight->delete(); return $this->redirectBack('Draft flight removed.');
    }
}
