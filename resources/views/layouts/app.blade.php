<!doctype html>
<html lang="en" data-layout="vertical" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Administration') · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/css/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('samis-asset/css/common.css') }}">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aviation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/legacy-aviation.css') }}">
</head>
<body class="aviation-app legacy-operations">
<div id="main-wrapper" class="aviation-shell legacy-shell">
    <aside class="left-sidebar with-vertical aviation-sidebar" id="av-sidebar">@include('partials.sidebar')</aside>
    <button class="av-nav-scrim" id="av-nav-scrim" type="button" aria-label="Close navigation"></button>
    <div class="page-wrapper aviation-main">
        <div class="body-wrapper">
            <header class="topbar aviation-topbar sticky-top">@include('partials.header')</header>
            <div class="container-fluid aviation-content legacy-content">
                <div class="legacy-page-heading"><div class="eyebrow">Administration workspace</div><h1>@yield('title','Administration')</h1></div>
                @if(session('success'))<div class="av-alerts av-success" role="status">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="av-alerts av-error" role="alert">{{ session('error') }}</div>@endif
                @if($errors->any())<div class="av-alerts av-error" role="alert"><strong>Please review the form</strong><ul>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></div>@endif
                @yield('content')
            </div>
            @include('partials.footer')
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/js/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/theme/app.init.js') }}"></script>
<script src="{{ asset('assets/js/theme/theme.js') }}"></script>
<script src="{{ asset('assets/js/theme/app.min.js') }}"></script>
<script src="{{ asset('assets/js/theme/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/toastr-init.js') }}"></script>
<script src="{{ asset('assets/iconify-icon/dist/iconify-icon.min.js') }}"></script>
<script src="{{ asset('assets/js/highlights/highlight.min.js') }}"></script>
<script src="{{ asset('samis-asset/js/common.js') }}"></script>
<script src="{{ asset('samis-asset/js/service.js') }}"></script>
<script src="{{ asset('samis-asset/js/div-control.js') }}"></script>
<script src="{{ asset('samis-asset/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/libs/jquery/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('assets/js/aviation-navigation.js') }}"></script>
<script>if(window.hljs)hljs.highlightAll();</script>
@stack('scripts')
@include('partials.modal')
</body>
</html>
