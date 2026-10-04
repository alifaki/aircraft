<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\SafetyEvent;

class SafetyEventController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.events',['events'=>SafetyEvent::with('duty','flight')->latest()->paginate(30)]);
    }
}
