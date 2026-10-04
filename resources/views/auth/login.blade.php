@extends('layouts.auth')
@section('title','Sign in')
@section('stage','Secure sign in')
@section('content')
<div class="eyebrow">Welcome back</div>
<h1>Sign in to operations</h1>
<p class="auth-lead">Use your assigned username and password to access the flight operations workspace.</p>
<form data-auth-form method="post" action="{{ route('login.post') }}">
    @csrf
    <div class="auth-field"><label for="username">Username</label><input id="username" name="username" type="text" autocomplete="username" value="{{ old('username') }}" placeholder="Enter your username" required autofocus></div>
    <div class="auth-field"><label for="password">Password</label><div class="auth-password-wrap"><input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required><button class="auth-reveal" type="button" data-password-toggle="password" aria-label="Show password">Show</button></div></div>
    <div class="auth-message" data-auth-message role="alert" aria-live="polite"></div>
    <button class="av-btn av-btn-primary auth-submit" type="submit">Sign in <i class="ti ti-arrow-right" aria-hidden="true"></i></button>
</form>
<div class="auth-support"><span>Need help accessing your account?</span><a href="{{ route('verify-account') }}">Reset password</a></div>
@endsection
