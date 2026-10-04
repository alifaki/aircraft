@extends('layouts.auth')
@section('title','Verify sign in')
@section('stage','Account verification')
@section('hero-title','Secure access for every flight.')
@section('hero-description','Confirm the one-time code to protect your operations account.')
@section('content')
<div class="eyebrow">Additional verification</div>
<h1>Verify your sign in</h1>
<p class="auth-lead">Enter the one-time code sent to your registered contact information.</p>
<form data-auth-form method="post" action="{{ url('/verify-login-otp') }}">
    @csrf
    <input type="hidden" name="username" value="{{ $username ?? '' }}">
    <div class="auth-field"><label for="confirmationCode">Verification code</label><input id="confirmationCode" name="confirmationCode" type="text" autocomplete="one-time-code" placeholder="Enter the one-time code" required autofocus></div>
    <div class="auth-message" data-auth-message role="alert" aria-live="polite"></div>
    <button class="av-btn av-btn-primary auth-submit" type="submit">Verify and continue <i class="ti ti-arrow-right" aria-hidden="true"></i></button>
</form>
<form class="auth-support" data-auth-resend data-resend-delay="60" method="post" action="{{ url('/resend-otp') }}">
    @csrf
    <input type="hidden" name="username" value="{{ $username ?? '' }}">
    <span>Code not received?</span><button type="submit" disabled>Resend code</button>
    <span class="auth-countdown" data-countdown aria-live="polite"></span>
</form>
@endsection
