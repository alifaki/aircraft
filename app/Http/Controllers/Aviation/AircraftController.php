<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{Aircraft,AircraftType,Airport};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AircraftController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.aircraft',['aircraft'=>Aircraft::with('type','homeAirport')->orderBy('registration')->paginate(30),
            'types'=>AircraftType::orderBy('icao_designator')->get(),'airports'=>Airport::orderBy('icao')->get()]);
    }
    private function fields(Request $request, ?Aircraft $aircraft=null): array
    {
        $data=$request->validate(['registration'=>['required','string','max:24',Rule::unique('aviation_aircraft')->ignore($aircraft?->id)],
            'aircraft_type_id'=>'required|exists:aviation_aircraft_types,id','home_airport_id'=>'nullable|exists:aviation_airports,id',
            'serial_number'=>'nullable|string|max:120','status'=>'required|in:active,grounded,retired',
            'airworthiness_expires_at'=>'nullable|date','insurance_expires_at'=>'nullable|date','notes'=>'nullable|string|max:2000']);
        $data['registration']=strtoupper(trim($data['registration'])); return $data;
    }
    public function store(Request $request)
    {
        $this->allow('manage'); Aircraft::create($this->fields($request)); return $this->redirectBack('Aircraft saved.');
    }
    public function update(Request $request, Aircraft $aircraft)
    {
        $this->allow('manage'); $aircraft->update($this->fields($request,$aircraft)); return $this->redirectBack('Aircraft updated. Check affected rosters.');
    }
    public function destroy(Aircraft $aircraft)
    {
        $this->allow('manage'); $aircraft->delete(); return $this->redirectBack('Aircraft removed.');
    }
}
