<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\ParkingEntryController;
use App\Http\Controllers\ParkingLocationController;
use Illuminate\Support\Facades\Route;

// ================== MOBILE AUTH ==================
Route::post('/login', [MobileAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [MobileAuthController::class, 'logout']);
    Route::get('/profile', [MobileAuthController::class, 'profile']);
    // ✅ ADD THIS (REQUIRED FOR TEST)
    Route::get('/user-location', [MobileAuthController::class, 'userLocation']);

    // ================== VEHICLE TYPES ==================
    Route::get('vehicle-types', [VehicleTypeController::class, 'index']);
    Route::get('vehicle-types/{vehicleType}', [VehicleTypeController::class, 'show']);
    Route::get('vehicle-types/{vehicleType}/parking-entries', [VehicleTypeController::class, 'parkingEntries']);

    // ================== PARKING ENTRIES ==================
    Route::get('parking-entries', [ParkingEntryController::class, 'index']);
    Route::get('parking-entries/active', [ParkingEntryController::class, 'activeParkings']);

    Route::post('vehicles-sync', [ParkingEntryController::class, 'syncVehicles']);

    // ================== PARKING LOCATIONS ==================
    Route::get('parking-locations', [ParkingLocationController::class, 'index']);
    Route::get('parking-locations/{parkingLocation}', [ParkingLocationController::class, 'show']);
    Route::get('parking-locations/{parkingLocation}/parking-entries', [ParkingLocationController::class, 'parkingEntries']);
});
