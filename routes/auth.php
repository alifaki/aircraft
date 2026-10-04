<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ConfirmationCodeController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Step 1: Show login page
Route::get('/', fn () => redirect()->route(auth()->check()
    ? (auth()->user()->hasPermission('aviation.view') ? 'dashboard' : 'aviation.my-roster') : 'login'))->name('index');
Route::view('/login', 'auth.login')->name('login');

// Step 2: Verify username
Route::view('/verify-account', 'auth.verify-account')->name('verify-account');

// Step 3: Show confirmation code form (now requires username in session)
Route::get('/verify-confirmation-code', function () {
    if (session()->has('password_reset_username')) {
        return view('auth.verify-confirmation-code', [
            'username' => session('password_reset_username')
        ]);
    }
    return redirect()->route('verify-account')
        ->with('error', 'Please verify your account first.');
})->name('verify-confirmation-code');

// Step 4: Show change password form (now checks for user ID in session)
Route::get('/change-password', function () {
    if (session()->has('password_reset_user_id')) {
        return view('auth.change-password');
    }
    return redirect()->route('verify-confirmation-code')
        ->with('error', 'Please enter your confirmation code first.');
})->name('change-password');

// Clear session values if needed
Route::get('/reset-flow', function () {
    session()->forget([
        'password_reset_username',
        'password_reset_user_id',
        'confirmationCode'
    ]);
    return redirect()->route('login');
})->name('reset-flow');

// Login logic
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware(['throttle:3,1']);

// Verify account and send confirmation code
Route::post('/verify-account', [ConfirmationCodeController::class, 'sendConfirmation'])
    ->name('verify-account.post')
    ->middleware(['throttle:3,1']);
    Route::post('/send-confirmation-code', [ConfirmationCodeController::class, 'resendConfirmation'])->name('resend-confirmation-code');
// Confirm the code
Route::post('/verify-confirmation-code', [ConfirmationCodeController::class, 'confirmCode'])
    ->name('verify-confirmation-code.post')
    ->middleware(['throttle:5,1']); // Increased to 5 attempts for better UX

// change password
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])
    ->name('reset-password.post');

// Authenticated group
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
