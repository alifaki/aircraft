<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PaymentFlowController;
use App\Http\Controllers\ParkingEntryController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiAuthController::class, 'apiLogin'])->middleware(['throttle:3,1']);
Route::post('/logout', [ApiAuthController::class, 'apiLogout'])->middleware(['throttle:3,1']);

Route::get('/active-bill/{plateNumber}', [ParkingEntryController::class, 'getActiveBill'])->middleware(['throttle:10,1']);
Route::post('/payment/initiate', [BillController::class, 'azamPayMobileCheckout'])->middleware(['throttle:10,1']);

Route::post('/azam-pay/callback', [BillController::class, 'azamPayCallback'])->middleware(['throttle:10,1']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('post-payment', [PaymentFlowController::class, 'store']);
    require 'parking.php';
});

// Add this as the last route in api.php
Route::fallback(function() {
    return response()->json([
        'status' => false,
        'message' => 'The requested endpoint does not exist.',
        'documentation' => url('/docs') // Optional: link to your API docs
    ], 404);
});
