@extends('layouts.auth')
@section('title','Set a new password')
@section('stage','Password recovery · Step 3 of 3')
@section('hero-title','Your next flight starts with secure access.')
@section('hero-description','Create a strong password to protect flight plans and crew records.')
@section('content')
<div class="eyebrow">One final step</div>
<h1>Set a new password</h1>
<p class="auth-lead">Choose a password that you have not used for another account.</p>
<form data-auth-form method="post" action="{{ route('reset-password.post') }}">
    @csrf
    <div class="auth-field"><label for="newPassword">New password</label><div class="auth-password-wrap"><input id="newPassword" name="newPassword" type="password" autocomplete="new-password" minlength="8" placeholder="Create a strong password" required autofocus><button type="button" class="auth-reveal" data-password-toggle="newPassword" aria-label="Show password">Show</button></div><small>At least 8 characters with an uppercase letter, lowercase letter, number and special character.</small></div>
    <div class="auth-field"><label for="newPassword_confirmation">Confirm new password</label><div class="auth-password-wrap"><input id="newPassword_confirmation" name="newPassword_confirmation" type="password" autocomplete="new-password" minlength="8" placeholder="Repeat your new password" required><button type="button" class="auth-reveal" data-password-toggle="newPassword_confirmation" aria-label="Show password">Show</button></div></div>
    <div class="auth-message" data-auth-message role="alert" aria-live="polite"></div>
    <button class="av-btn av-btn-primary auth-submit" type="submit">Update password <i class="ti ti-arrow-right" aria-hidden="true"></i></button>
</form>
<div class="auth-support"><a href="{{ route('login') }}"><i class="ti ti-arrow-left" aria-hidden="true"></i> Return to sign in</a></div>
@endsection
