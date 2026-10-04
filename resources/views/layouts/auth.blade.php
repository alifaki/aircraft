<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Account access') · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aviation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aviation-auth.css') }}">
</head>
<body class="aviation-app auth-app">
<main class="auth-shell">
    <aside class="auth-story" aria-label="About flight operations">
        <a class="av-brand" href="{{ route('index') }}"><span class="av-mark" aria-hidden="true">✈</span><span><strong>{{ config('app.name') }}</strong><small>Operations control</small></span></a>
        <div class="auth-story-center">
            <span class="auth-overline">FLIGHT OPERATIONS WORKSPACE</span>
            <h2>@yield('hero-title','Every flight begins with a safe plan.')</h2>
            <p>@yield('hero-description','Plan aircraft availability, crew assignments and duty limits in one connected workspace.')</p>
            <div class="auth-story-points"><span><i class="ti ti-shield-check" aria-hidden="true"></i> Qualified crew</span><span><i class="ti ti-clock-check" aria-hidden="true"></i> Managed duty</span><span><i class="ti ti-plane" aria-hidden="true"></i> Planned flights</span></div>
        </div>
        <small class="auth-story-footer">Scheduling and safety checks use UTC.</small>
    </aside>
    <section class="auth-panel">
        <div class="auth-panel-top"><span class="auth-step">@yield('stage','Account access')</span><a href="{{ route('login') }}">Back to sign in <i class="ti ti-arrow-up-right" aria-hidden="true"></i></a></div>
        <div class="auth-panel-content">
            @if(session('error'))<div class="auth-message is-error" role="alert">{{ session('error') }}</div>@endif
            @yield('content')
        </div>
        <div class="auth-panel-foot">Authorised personnel only · {{ config('app.name') }}</div>
    </section>
</main>
<script src="{{ asset('assets/js/aviation-auth.js') }}"></script>
@stack('scripts')
</body>
</html>
