<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\AircraftType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AircraftTypeController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.types',['types'=>AircraftType::orderBy('icao_designator')->paginate(30)]);
    }
    private function fields(Request $request, ?AircraftType $type=null): array
    {
        $data=$request->validate(['icao_designator'=>['required','alpha_num','max:8',Rule::unique('aviation_aircraft_types')->ignore($type?->id)],
            'manufacturer'=>'nullable|string|max:120','model'=>'required|string|max:120',
            'minimum_pilots'=>'required|integer|min:1|max:4','minimum_cabin_crew'=>'required|integer|min:0|max:30',
            'minimum_turnaround_minutes'=>'required|integer|min:0|max:1440',
            'passenger_capacity'=>'nullable|integer|min:0|max:999']);
        $data['icao_designator']=strtoupper($data['icao_designator']); return $data;
    }
    public function store(Request $request)
    {
        $this->allow('manage'); AircraftType::create($this->fields($request)); return $this->redirectBack('Aircraft type saved.');
    }
    public function update(Request $request, AircraftType $type)
    {
        $this->allow('manage'); $type->update($this->fields($request,$type)); return $this->redirectBack('Aircraft type updated.');
    }
    public function destroy(AircraftType $type)
    {
        $this->allow('manage'); $type->delete(); return $this->redirectBack('Aircraft type removed.');
    }
}
