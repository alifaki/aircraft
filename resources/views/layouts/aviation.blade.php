<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Operations') · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aviation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
</head>
<body class="aviation-app">
<div class="aviation-shell">
    <aside class="aviation-sidebar" id="av-sidebar">
        @include('partials.sidebar')
    </aside>
    <button class="av-nav-scrim" id="av-nav-scrim" type="button" aria-label="Close navigation"></button>
    <main class="aviation-main">
        <header class="aviation-topbar">@include('partials.header')</header>
        <div id="av-feedback" role="status" aria-live="polite"></div><div class="aviation-content" id="av-content">
            @if(session('aviation_success')) <div class="av-alerts av-success" role="status">{{ session('aviation_success') }}</div> @endif
            @if(session('aviation_warning')) <div class="av-alerts av-warning" role="alert">{{ session('aviation_warning') }}</div> @endif
            @if($errors->any())<div class="av-alerts av-error" role="alert"><strong>Action needs attention</strong><ul>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </div>
    </main>
</div>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/aviation-navigation.js') }}"></script>
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/aviation-ajax.js') }}"></script>
</body>
</html>
