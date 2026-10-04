<?php
namespace App\Http\Controllers\Aviation;

use App\Models\Aviation\FatiguePolicy;
use Illuminate\Http\Request;

class PolicyController extends AviationController
{
    public function index()
    {
        $this->allow('view');
        return view('aviation.policies',['policies'=>FatiguePolicy::orderByDesc('id')->paginate(30)]);
    }
    private function fields(Request $request): array
    {
        $rules=['name'=>'required|string|max:200','crew_category'=>'required|in:pilot,cabin',
            'source_reference'=>'required|string|max:255','effective_from'=>'nullable|date',
            'effective_until'=>'nullable|date|after_or_equal:effective_from','notes'=>'nullable|string|max:3000'];
        foreach (FatiguePolicy::LIMIT_FIELDS as $key) $rules[$key]='nullable|integer|min:1|max:1000000';
        return $request->validate($rules);
    }
    public function store(Request $request)
    {
        $this->allow('policy'); FatiguePolicy::create($this->fields($request)+['status'=>'draft']);
        return $this->redirectBack('Policy draft saved. Add all applicable values, then approve it.');
    }
    public function update(Request $request, FatiguePolicy $policy)
    {
        $this->allow('policy');
        if ($policy->status!=='draft') return back()->withErrors(['policy'=>'Approved policy is immutable. Create a new draft for revised limits.']);
        $policy->update($this->fields($request)); return $this->redirectBack('Policy draft updated.');
    }
    public function approve(FatiguePolicy $policy)
    {
        $this->allow('policy');
        if ($policy->status!=='draft' || !($policy->effective_from && $policy->effective_until) || !$policy->source_reference)
            return back()->withErrors(['policy'=>'Add the source and both effective dates before approving.']);
        foreach (FatiguePolicy::LIMIT_FIELDS as $field) {
            if ($policy->crew_category==='cabin' && str_starts_with($field,'max_flight_')) continue;
            if (!$policy->{$field}) return back()->withErrors(['policy'=>"Set {$field} before approving. The scanned page does not supply all required limits."]);
        }
        $policy->update(['status'=>'approved','approved_at'=>now(),'approved_by'=>auth()->id()]);
        return $this->redirectBack('Fatigue policy approved and available for scheduling.');
    }
    public function destroy(FatiguePolicy $policy)
    {
        $this->allow('policy');
        if ($policy->status!=='draft') return back()->withErrors(['policy'=>'Keep approved policies as immutable audit records.']);
        $policy->delete(); return $this->redirectBack('Draft removed.');
    }
}
