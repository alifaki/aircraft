@extends('layouts.auth')
@section('title','Confirm your code')
@section('stage','Password recovery · Step 2 of 3')
@section('hero-title','Your account, verified and protected.')
@section('hero-description','Enter the code sent to your registered contact to continue resetting your password.')
@section('content')
<div class="eyebrow">Verify your identity</div>
<h1>Enter confirmation code</h1>
<p class="auth-lead">We sent a code for <strong>{{ $username }}</strong>. It expires after five minutes.</p>
<form data-auth-form method="post" action="{{ route('verify-confirmation-code.post') }}">
    @csrf
    <input type="hidden" name="username" value="{{ $username }}">
    <div class="auth-field"><label for="confirmationCode">Confirmation code</label><input id="confirmationCode" name="confirmationCode" type="text" autocomplete="one-time-code" placeholder="SAMIS-1-2-3-4-5-6" required autofocus><small>Enter the complete code from your message, including its prefix if shown.</small></div>
    <div class="auth-message" data-auth-message role="alert" aria-live="polite"></div>
    <button class="av-btn av-btn-primary auth-submit" type="submit">Verify code <i class="ti ti-arrow-right" aria-hidden="true"></i></button>
</form>
<form class="auth-support" data-auth-resend data-resend-delay="300" method="post" action="{{ route('resend-confirmation-code') }}">
    @csrf
    <input type="hidden" name="username" value="{{ $username }}">
    <span>Didn't receive it?</span>
    <button type="submit" disabled>Resend code</button>
    <span class="auth-countdown" data-countdown aria-live="polite"></span>
</form>
<div class="auth-support"><a href="{{ route('verify-account') }}"><i class="ti ti-arrow-left" aria-hidden="true"></i> Use a different account</a></div>
@endsection
