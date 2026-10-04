@extends('layouts.auth')
@section('title','Recover account')
@section('stage','Password recovery · Step 1 of 3')
@section('hero-title','A secure way back to your workspace.')
@section('hero-description','Verify your account, confirm the code, and choose a new password.')
@section('content')
<div class="eyebrow">Verify your identity</div>
<h1>Recover your account</h1>
<p class="auth-lead">Enter your username. A confirmation code will be sent to the contact details registered to your account.</p>
<form data-auth-form method="post" action="{{ route('verify-account.post') }}">
    @csrf
    <div class="auth-field"><label for="username">Username</label><input id="username" name="username" type="text" autocomplete="username" value="{{ old('username') }}" placeholder="Enter your username" required autofocus></div>
    <div class="auth-message" data-auth-message role="alert" aria-live="polite"></div>
    <button class="av-btn av-btn-primary auth-submit" type="submit">Send confirmation code <i class="ti ti-arrow-right" aria-hidden="true"></i></button>
</form>
<div class="auth-support"><a href="{{ route('login') }}"><i class="ti ti-arrow-left" aria-hidden="true"></i> Return to sign in</a></div>
@endsection
