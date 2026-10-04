<a class="av-brand" href="{{ route(auth()->user()->hasPermission('aviation.view') ? 'aviation.dashboard' : 'aviation.my-roster') }}">
    <span class="av-mark" aria-hidden="true">✈</span>
    <span><strong>{{ config('app.name') }}</strong><small>Flight operations</small></span>
</a>
<nav aria-label="Main navigation">
    <div class="av-nav-title">Your workspace</div>
    <a class="av-link {{ request()->routeIs('aviation.my-roster') ? 'active' : '' }}" href="{{ route('aviation.my-roster') }}"><i class="ti ti-user-check" aria-hidden="true"></i> My roster</a>
    @if(auth()->user()->hasPermission('aviation.view'))
        <div class="av-nav-title">Operations</div>
        @foreach([
            ['aviation.dashboard','Overview','ti-layout-dashboard'],
            ['aviation.flights.index','Flight schedule','ti-plane-departure'],
            ['aviation.duties.index','Crew duties','ti-calendar-time'],
            ['aviation.crew.index','Crew & ratings','ti-users'],
        ] as [$name,$label,$icon])
            <a class="av-link {{ request()->routeIs($name) ? 'active' : '' }}" href="{{ route($name) }}"><i class="ti {{ $icon }}" aria-hidden="true"></i> {{ $label }}</a>
        @endforeach
        <div class="av-nav-title">Resources & compliance</div>
        @foreach([
            ['aviation.aircraft.index','Aircraft','ti-plane'],
            ['aviation.automation.index','Maintenance & pilot leave','ti-calendar-time'],
            ['aviation.types.index','Aircraft types','ti-adjustments'],
            ['aviation.airports.index','Airports','ti-map-pin'],
            ['aviation.absences.index','Crew availability','ti-calendar-off'],
            ['aviation.groundings.index','Groundings','ti-tool'],
            ['aviation.policies.index','Fatigue policies','ti-shield-check'],
            ['aviation.events.index','Safety events','ti-alert-triangle'],
        ] as [$name,$label,$icon])
            <a class="av-link {{ request()->routeIs($name) ? 'active' : '' }}" href="{{ route($name) }}"><i class="ti {{ $icon }}" aria-hidden="true"></i> {{ $label }}</a>
        @endforeach
        @if(auth()->user()->hasPermission('aviation.manage'))
            <div class="av-nav-title">Administration</div>
            @foreach([
                ['staffs','Staff records','ti-user-plus'],
                ['users','User accounts','ti-user-circle'],
                ['branches','Branches','ti-building'],
                ['departments','Departments','ti-sitemap'],
                ['sections','Sections','ti-layout-grid'],
                ['roles','Roles & access','ti-lock'],
                ['permissions','Permissions','ti-key'],
                ['audit-trails','Audit trail','ti-list-details'],
                ['profile','My profile','ti-user'],
            ] as [$path,$label,$icon])
                @if(auth()->user()->hasPermission('url:'.$path))
                    <a class="av-link {{ request()->is($path) ? 'active' : '' }}" href="{{ url($path) }}"><i class="ti {{ $icon }}" aria-hidden="true"></i> {{ $label }}</a>
                @endif
            @endforeach
        @endif
    @endif
</nav>
<div class="av-side-note"><strong>All times are UTC</strong>Flight and crew scheduling use UTC for safety checks.</div>
