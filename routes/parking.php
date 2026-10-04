<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipalController;
use App\Http\Controllers\ParkingLocationController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\ParkingEntryController;

Route::apiResource('municipals', MunicipalController::class);
Route::get('municipals/{municipal}/parking-locations', [MunicipalController::class, 'parkingLocations']);
// Parking Location Routes
Route::apiResource('parking-locations', ParkingLocationController::class);
Route::get('parking-locations/{parkingLocation}/parking-entries', [ParkingLocationController::class, 'parkingEntries']);

// Vehicle Type Routes
Route::apiResource('vehicle-types', VehicleTypeController::class);
Route::get('vehicle-types/{vehicleType}/parking-entries', [VehicleTypeController::class, 'parkingEntries']);

// Parking Entry Routes
Route::apiResource('parking-entries', ParkingEntryController::class);
Route::put('parking-entries/{parkingEntry}/checkout', [ParkingEntryController::class, 'checkout']);
Route::get('parking-entries/active', [ParkingEntryController::class, 'activeParkings']);
Route::get('parking-entries/reports', [ParkingEntryController::class, 'reports']);