<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IpRestrictionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SectionController;
// use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController; // ✅ Add this line
use Illuminate\Support\Facades\Route;

require 'auth.php';

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/dashboard', [\App\Http\Controllers\Aviation\DashboardController::class, 'index'])->name('dashboard');

    require 'aviation.php';

    Route::get('/{page}', [PageController::class, 'show']);

    Route::prefix('web/v1')->group(function () {

        require 'parking.php';

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'getProfile'])->name('profile');
            Route::post('/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
            Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
            Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        });
        Route::resource('companies', CompanyController::class);
        // Audit Trails
        Route::get('audit-trails', [AuditTrailController::class, 'index']);
        // IP Restrictions
        Route::resource('ip-restrictions', IpRestrictionController::class)->except(['show']);
        // Branch Routes
        Route::resource('branches', BranchController::class);
        Route::post('branches/{branch}/assign-head', [BranchController::class, 'assignHead']);
        // Brand API Routes
        Route::resource('users', UserController::class);
        Route::resource('staff', StaffController::class);
        Route::post('staff/{staff}/create-user-account', [StaffController::class, 'createUserAccount']);
        // User Routes
        Route::prefix('users')->group(function () {
            // Additional custom routes
            Route::post('/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::post('/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        });

        Route::resource('departments', DepartmentController::class);
        // Department Routes
        Route::prefix('departments')->group(function () {
            Route::post('/{id}/assign-head', [DepartmentController::class, 'assignHead']);
            Route::post('/{id}/restore', [DepartmentController::class, 'restore']);
        });

        Route::resource('sections', SectionController::class);
        // Section Routes
        Route::prefix('sections')->group(function () {
            Route::get('/{id}/departments', [SectionController::class, 'byDepartment']); // section by department
            Route::post('/{id}/assign-head', [SectionController::class, 'assignHead']);
            Route::get('/{id}/staff', [SectionController::class, 'staff']);
            Route::post('/{id}/restore', [SectionController::class, 'restore']);
        });

        Route::resource('roles', RoleController::class);
        // Role Routes
        Route::prefix('roles')->group(function () {
            Route::post('/{role}/assign-permissions', [RoleController::class, 'assignPermissions']);
            Route::get('/{role}/available-permissions', [RoleController::class, 'availablePermissions']);
            Route::get('/{role}/users', [RoleController::class, 'users']);
            Route::post('{role}/remove-permissions', [RoleController::class, 'removePermissions'])
                ->name('roles.remove-permissions');
        });

        Route::resource('permissions', PermissionController::class)->except(['show']);
        Route::get('permissions/modules', [PermissionController::class, 'modules'])->name('permissions.modules');
        Route::get('permissions/{permission}/roles', [PermissionController::class, 'getAssignedRoles'])->name('permissions.roles');
        Route::post('permissions/{permission}/assign-roles', [PermissionController::class, 'assignToRoles'])->name('permissions.assign-roles');

        // ==================== NEW PAYMENT / SEARCH PLATE ROUTES ====================
        Route::prefix('payments')->group(function () {
            Route::get('/search-vehicle', [PaymentController::class, 'index'])->name('payments.search');
            Route::get('/search-vehicle/ajax', [PaymentController::class, 'searchPlate'])->name('payments.search.ajax');
        });
        // ========================================================================

    });
});
// Add this as the last route in web.php
Route::fallback(function() {
    return response()->view('errors.custom-404', [], 404);
});
