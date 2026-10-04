<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('code','Error') · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aviation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aviation-auth.css') }}">
</head>
<body class="aviation-app auth-app">
<main class="auth-shell">
    <aside class="auth-story">
        <a class="av-brand" href="{{ url('/') }}"><span class="av-mark" aria-hidden="true">✈</span><span><strong>{{ config('app.name') }}</strong><small>Flight operations</small></span></a>
        <div class="auth-story-center"><span class="auth-overline">OPERATIONS WORKSPACE</span><h2>We’ll help you get back on course.</h2><p>The rest of your operations data is safe. Return to the start page to continue.</p></div>
        <small class="auth-story-footer">Scheduling and safety checks use UTC.</small>
    </aside>
    <section class="auth-panel">
        <div class="auth-panel-top"><span class="auth-step">Request status</span><a href="{{ url('/') }}">Go to start <i class="ti ti-arrow-up-right" aria-hidden="true"></i></a></div>
        <div class="auth-panel-content"><div class="eyebrow">Request could not be completed</div><div class="error-code">@yield('code','Error')</div><h1>@yield('heading','Something went wrong')</h1><p class="auth-lead">@yield('message','Please try again later.')</p><a class="av-btn av-btn-primary" href="{{ url('/') }}">Return to the start page</a></div>
        <div class="auth-panel-foot">{{ config('app.name') }}</div>
    </section>
</main>
<style>.error-code{font-size:clamp(78px,10vw,132px);font-weight:850;line-height:1;letter-spacing:-.08em;color:#087e83;margin:10px 0 18px}.auth-panel-content>a.av-btn{margin-top:8px}</style>
</body>
</html>
