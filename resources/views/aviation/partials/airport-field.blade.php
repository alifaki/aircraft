<div><label>{{ $label }}</label>
@if($field === 'timezone')
<select name="timezone" class="form-select" required><option value="">Choose time zone</option>
@foreach(\DateTimeZone::listIdentifiers(\DateTimeZone::ALL) as $zone)<option value="{{ $zone }}" @selected($value === $zone)>{{ str_replace('_',' ',$zone) }}</option>@endforeach
</select>
@elseif($field === 'country')
<select name="country" data-countries class="form-select" required><option value="">Choose country</option>@if($value)<option value="{{ $value }}" selected>{{ $value }}</option>@endif</select>
@else<input name="{{ $field }}" value="{{ $value }}" class="form-control" {{ in_array($field,['icao','name','city'])?'required':'' }} @if($field==='icao') maxlength="4" placeholder="HTZA" @elseif($field==='iata') maxlength="3" placeholder="ZNZ" @endif>
@endif</div>
