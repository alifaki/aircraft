<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\{Qualification,CrewMember};
use Illuminate\Http\Request;

class QualificationController extends AviationController
{
    public function store(Request $request)
    {
        $this->allow('manage');
        $data=$request->validate(['crew_id'=>'required|exists:aviation_crew,id','aircraft_type_id'=>'required|exists:aviation_aircraft_types,id',
            'role'=>'required|in:captain,first_officer,cabin_crew','certificate_number'=>'nullable|string|max:120','expires_at'=>'required|date']);
        $crew=CrewMember::findOrFail($data['crew_id']);
        if (($crew->crew_category==='pilot') !== in_array($data['role'],['captain','first_officer'],true))
            return back()->withErrors(['role'=>'The qualification role must match the crew category.']);
        Qualification::updateOrCreate(['crew_id'=>$data['crew_id'],'aircraft_type_id'=>$data['aircraft_type_id'],'role'=>$data['role']],
            ['certificate_number'=>$data['certificate_number'] ?? null,'expires_at'=>$data['expires_at']]);
        return $this->redirectBack('Type qualification saved.');
    }
    public function destroy(Qualification $qualification)
    {
        $this->allow('manage'); $qualification->delete(); return $this->redirectBack('Qualification removed.');
    }
}
