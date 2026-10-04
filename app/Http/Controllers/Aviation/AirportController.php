<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\Airport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AirportController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.airports', ['airports'=>Airport::orderBy('icao')->paginate(30)]);
    }
    public function store(Request $request)
    {
        $this->allow('manage');
        $data=$request->validate(['icao'=>'required|alpha|size:4|unique:aviation_airports,icao',
            'iata'=>'nullable|alpha|size:3','name'=>'required|string|max:200','city'=>'required|string|max:120',
            'country'=>'required|string|max:120','timezone'=>'required|timezone','active'=>'required|boolean']);
        $data['icao']=strtoupper($data['icao']);
        $data['iata']=strtoupper($data['iata'] ?? '');
        Airport::create($data); return $this->redirectBack('Airport saved.');
    }
    public function update(Request $request, Airport $airport)
    {
        $this->allow('manage');
        $data=$request->validate(['icao'=>['required','alpha','size:4',Rule::unique('aviation_airports')->ignore($airport->id)],
            'iata'=>'nullable|alpha|size:3','name'=>'required|string|max:200','city'=>'required|string|max:120',
            'country'=>'required|string|max:120','timezone'=>'required|timezone','active'=>'required|boolean']);
        $data['icao']=strtoupper($data['icao']); $data['iata']=strtoupper($data['iata'] ?? '');
        $airport->update($data); return $this->redirectBack('Airport updated.');
    }
    public function destroy(Airport $airport)
    {
        $this->allow('manage'); $airport->delete(); return $this->redirectBack('Airport removed.');
    }
}
