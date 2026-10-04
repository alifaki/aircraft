<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Staff;
use App\Models\Aviation\{CrewMember,Airport,AircraftType};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CrewController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.crew',['crew'=>CrewMember::with('staff','baseAirport','qualifications.aircraftType')->orderBy('id')->paginate(30),
            'staff'=>Staff::where('status','active')->whereDoesntHave('crewProfile')->orderBy('first_name')->get(),
            'airports'=>Airport::orderBy('icao')->get(),'types'=>AircraftType::orderBy('icao_designator')->get()]);
    }
    private function fields(Request $request, ?CrewMember $crew=null): array
    {
        return $request->validate(['staff_id'=>['required','exists:staff,id',Rule::unique('aviation_crew')->ignore($crew?->id)],
            'crew_category'=>'required|in:pilot,cabin','base_airport_id'=>'nullable|exists:aviation_airports,id',
            'licence_number'=>'required|string|max:120','licence_expires_at'=>'required|date',
            'medical_expires_at'=>'required|date','status'=>'required|in:active,inactive','notes'=>'nullable|string|max:2000']);
    }
    public function store(Request $request)
    {
        $this->allow('manage'); CrewMember::create($this->fields($request)); return $this->redirectBack('Crew profile saved. Add type qualifications before rostering.');
    }
    public function update(Request $request, CrewMember $crew)
    {
        $this->allow('manage'); $data=$this->fields($request,$crew);
        if (($data['staff_id']!=$crew->staff_id || $data['crew_category']!==$crew->crew_category) && $crew->duties()->whereIn('status',['published','completed'])->exists())
            return back()->withErrors(['crew'=>'Unpublish related rosters before changing this crew identity.']);
        $crew->update($data); return $this->redirectBack('Crew profile updated. Check affected rosters.');
    }
    public function destroy(CrewMember $crew)
    {
        $this->allow('manage'); $crew->delete(); return $this->redirectBack('Crew profile removed.');
    }
}
