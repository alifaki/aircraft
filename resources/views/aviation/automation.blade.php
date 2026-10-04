@extends('layouts.aviation')
@section('title','Maintenance & pilot leave')
@section('content')
<div class="page-heading"><div><div class="eyebrow">Automatic planning</div><h1>Maintenance & pilot leave</h1><p>Track actual aircraft usage, upcoming checks and recovery leave. All dates and times are UTC.</p></div>
@if(auth()->user()->hasPermission('aviation.manage'))<form method="post" action="{{ route('aviation.automation.sync') }}">@csrf<button class="av-btn av-btn-primary">Update schedules now</button></form>@endif</div>
<div class="av-card"><div class="av-card-body"><p class="av-sub">Maintenance uses the first limit reached: flight hours, landing cycles or calendar days. Enter intervals from your maintenance programme. Recovery leave uses your operator's flight-hour leave rule. These rules work alongside the existing fatigue checks.</p></div></div>
@if(auth()->user()->hasPermission('aviation.manage'))
<div class="av-card"><div class="av-card-head"><h2>Create recurring maintenance plan</h2></div><div class="av-card-body">
<form method="post" action="{{ route('aviation.automation.maintenance') }}" class="av-form-grid">@csrf
<div><label>Aircraft</label><select name="aircraft_id" class="form-select" required><option value="">Choose aircraft</option>@foreach($aircraft as $plane)<option value="{{ $plane->id }}">{{ $plane->registration }}</option>@endforeach</select></div>
<div><label>Maintenance check</label><select name="name" class="form-select" required><option value="">Choose check</option>@foreach(['Routine inspection','50-hour inspection','100-hour inspection','A check','B check','C check','D check','Annual inspection','Engine inspection','Component replacement','Airworthiness directive','Other scheduled check'] as $check)<option>{{ $check }}</option>@endforeach</select></div>
<div class="span2"><label>Programme / component / manual reference</label><input name="source_reference" class="form-control" required maxlength="255" placeholder="Approved programme reference and check details"></div>
<div><label>Flight-hour interval (optional)</label><input type="number" step="0.1" min="0.1" max="100000" name="interval_hours" class="form-control"></div>
<div><label>Landing-cycle interval (optional)</label><input type="number" min="1" max="1000000" name="interval_cycles" class="form-control"></div>
<div><label>Calendar interval in days (optional)</label><input type="number" min="1" max="36500" name="interval_days" class="form-control"></div>
<div><label>Last maintenance completed UTC</label><input type="datetime-local" name="last_completed_at" class="form-control" required></div>
<div><label>Total aircraft hours at last maintenance</label><input type="number" step="0.1" min="0" max="10000000" value="0" name="baseline_hours" class="form-control" required></div>
<div><label>Total landing cycles at last maintenance</label><input type="number" min="0" max="100000000" value="0" name="baseline_cycles" class="form-control" required></div>
<div><label>Warn this many hours before due</label><input type="number" min="0" max="10000" step="0.1" value="10" name="warning_hours" class="form-control" required></div>
<div><label>Warn this many cycles before due</label><input type="number" min="0" max="100000" value="10" name="warning_cycles" class="form-control" required></div>
<div><label>Warn this many days before due</label><input type="number" min="0" max="3650" value="7" name="warning_days" class="form-control" required></div>
<div class="span2 av-sub">Set at least one interval. Recorded flights after the last maintenance are added to its meter readings. Make sure those actual flights have been entered.</div>
<div><button class="av-btn av-btn-primary">Create maintenance plan</button></div>
</form></div></div>@endif
<div class="av-card"><div class="av-card-head"><h2>Aircraft maintenance schedule</h2><button class="av-btn av-btn-outline" type="button" data-refresh-table>Refresh table</button></div>
<div class="table-responsive"><table class="table av-table"><thead><tr><th>Aircraft / check</th><th>Current meter</th><th>Remaining usage</th><th>Calendar due UTC</th><th>Status / slot</th><th>Actions / history</th></tr></thead><tbody>
@forelse($plans as $plan)
@php($state=$service->maintenanceState($plan))
@php($task=$plan->tasks->whereNull('completed_at')->first())
<tr><td><strong>{{ $plan->aircraft?->registration }} · {{ $plan->name }}</strong><div class="av-sub">{{ $plan->source_reference }}</div></td>
<td>{{ number_format($state['minutes']/60,1) }} hours<br>{{ $state['cycles'] }} cycles</td>
<td>{{ $state['remaining_minutes']!==null ? number_format($state['remaining_minutes']/60,1).' hours' : 'No hour interval' }}<br>{{ $state['remaining_cycles']!==null ? $state['remaining_cycles'].' cycles' : 'No cycle interval' }}</td>
<td>{{ $state['due_at']?->format('d M Y H:i') ?? 'Usage based' }}</td>
<td><span class="av-badge {{ $state['status']==='due'?'grounded':($state['status']==='upcoming'?'draft':'active') }}">{{ $plan->active ? ucfirst($state['status']) : 'Inactive' }}</span>@if($task?->scheduled_start)<div class="av-sub">{{ $task->scheduled_start->format('d M Y H:i') }} → {{ $task->scheduled_end->format('d M Y H:i') }}</div>@endif</td>
<td>@if(auth()->user()->hasPermission('aviation.policy'))<details class="av-details"><summary>Edit programme</summary><form method="post" action="{{ route('aviation.automation.maintenance.update',$plan) }}" class="av-form-grid">@csrf @method('PUT')
<div><label>Status</label><select name="active" class="form-select"><option value="1" @selected($plan->active)>Active</option><option value="0" @selected(!$plan->active)>Inactive</option></select></div>
<div><label>Reference</label><input name="source_reference" class="form-control" required value="{{ $plan->source_reference }}"></div>
<div><label>Hour interval</label><input name="interval_hours" type="number" min="0.1" max="100000" step="0.1" class="form-control" value="{{ $plan->interval_minutes ? $plan->interval_minutes/60 : '' }}"></div>
<div><label>Cycle interval</label><input name="interval_cycles" type="number" min="1" max="1000000" class="form-control" value="{{ $plan->interval_cycles }}"></div>
<div><label>Day interval</label><input name="interval_days" type="number" min="1" max="36500" class="form-control" value="{{ $plan->interval_days }}"></div>
<div><button class="av-btn av-btn-primary">Save programme</button></div></form></details>@endif
@if($task && auth()->user()->hasPermission('aviation.manage'))<details class="av-details"><summary>Book maintenance</summary><form method="post" action="{{ route('aviation.automation.book',$task) }}" class="av-form-grid">@csrf<div><label>Starts UTC</label><input type="datetime-local" name="scheduled_start" class="form-control" required></div><div><label>Ends UTC</label><input type="datetime-local" name="scheduled_end" class="form-control" required></div><div><button class="av-btn av-btn-soft">Book slot</button></div></form></details>@endif
@if($task && auth()->user()->hasPermission('aviation.publish'))<details class="av-details"><summary>Complete maintenance</summary><form method="post" action="{{ route('aviation.automation.complete',$task) }}">@csrf<label>Work order / release reference</label><input name="completion_reference" class="form-control mb-2" maxlength="2000" required><button class="av-btn av-btn-primary">Confirm completed work</button></form></details>@endif
<details class="av-details"><summary>Completion history</summary>@forelse($plan->tasks->whereNotNull('completed_at')->sortByDesc('completed_at') as $done)<div class="av-sub">{{ $done->completed_at->format('d M Y H:i') }} · {{ $done->completion_reference }}</div>@empty<span class="av-sub">No completed tasks yet.</span>@endforelse</details></td></tr>
@empty<tr><td colspan="6" class="av-empty">Create a plan to calculate maintenance due dates and usage limits.</td></tr>@endforelse
</tbody></table></div></div>
@if(auth()->user()->hasPermission('aviation.policy'))
<div class="av-card"><div class="av-card-head"><h2>Configure automatic pilot recovery leave</h2></div><div class="av-card-body">
<form method="post" action="{{ route('aviation.automation.leave-rule') }}" class="av-form-grid">@csrf
<div><label>Pilot</label><select name="crew_id" class="form-select" required><option value="">Choose pilot</option>@foreach($pilots as $pilot)@if(!$rules->contains('crew_id',$pilot->id))<option value="{{ $pilot->id }}">{{ $pilot->staff?->first_name }} {{ $pilot->staff?->last_name }} · {{ $pilot->licence_number }}</option>@endif @endforeach</select></div>
<div><label>Flight-hour threshold</label><input name="threshold_hours" type="number" step="0.1" min="0.1" max="100000" class="form-control" required></div>
<div><label>Recovery leave duration in days</label><input name="leave_days" type="number" min="1" max="365" class="form-control" required></div>
<div><label>Tracking begins UTC</label><input type="datetime-local" name="tracking_from" class="form-control" required></div>
<div><label>Unlogged hours at tracking start</label><input name="baseline_hours" type="number" min="0" max="100000" step="0.1" value="0" class="form-control" required></div>
<div class="span2"><label>Operator leave policy reference</label><input name="source_reference" class="form-control" required maxlength="255"></div>
<div><button class="av-btn av-btn-primary">Activate leave rule</button></div>
<div class="span4 av-sub">Actual flight hours count once per pilot and flight. At the threshold, recovery leave begins immediately, or after a duty already underway. Conflicting future published duties are flagged for replacement. The hour counter resets after the leave ends.</div>
</form></div></div>@endif
<div class="av-card"><div class="av-card-head"><h2>Pilot flight hours & automatic leave</h2><button type="button" class="av-btn av-btn-outline" data-refresh-table>Refresh table</button></div><div class="table-responsive"><table class="table av-table"><thead><tr><th>Pilot</th><th>Hours / threshold</th><th>Remaining</th><th>Recovery leave</th><th>Policy reference</th></tr></thead><tbody>
@forelse($rules as $rule)@php($minutes=$service->pilotMinutes($rule))@php($leave=$rule->absences->sortByDesc('ends_at')->first())
<tr><td><strong>{{ $rule->crew?->staff?->first_name }} {{ $rule->crew?->staff?->last_name }}</strong></td><td>{{ number_format($minutes/60,1) }} / {{ number_format($rule->threshold_minutes/60,1) }} hours</td><td>{{ number_format(max(0,$rule->threshold_minutes-$minutes)/60,1) }} hours</td><td>@if($leave){{ $leave->starts_at->format('d M Y H:i') }} → {{ $leave->ends_at->format('d M Y H:i') }} UTC<div class="av-sub">{{ $leave->ends_at->isFuture()?'Scheduled / in progress':'Completed' }}</div>@else<span class="av-sub">Generated automatically at threshold</span>@endif</td><td>{{ $rule->source_reference }}<div class="av-sub">{{ $rule->leave_days }} days of recovery leave · {{ $rule->active?'Active':'Inactive' }}</div>
@if(auth()->user()->hasPermission('aviation.policy'))<details class="av-details"><summary>Edit rule</summary><form method="post" action="{{ route('aviation.automation.leave-rule.update',$rule) }}" class="av-form-grid">@csrf @method('PUT')
<div><label>Threshold hours</label><input name="threshold_hours" type="number" step="0.1" min="0.1" max="100000" required class="form-control" value="{{ $rule->threshold_minutes/60 }}"></div>
<div><label>Leave days</label><input name="leave_days" type="number" min="1" max="365" required class="form-control" value="{{ $rule->leave_days }}"></div>
<div><label>Policy reference</label><input name="source_reference" class="form-control" required value="{{ $rule->source_reference }}"></div>
<div><label>Status</label><select name="active" class="form-select"><option value="1" @selected($rule->active)>Active</option><option value="0" @selected(!$rule->active)>Inactive</option></select></div>
<div><button class="av-btn av-btn-primary">Save rule</button></div></form></details>@endif</td></tr>
@empty<tr><td colspan="5" class="av-empty">An administrator with policy permission can configure flight-hour recovery leave.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
