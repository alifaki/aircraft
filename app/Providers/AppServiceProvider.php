<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('ip.restrict', \App\Http\Middleware\IpRestrictionMiddleware::class);

        // Register observers for models you want to track
        \App\Models\User::observe(\App\Observers\AuditTrailObserver::class);
        \App\Models\Department::observe(\App\Observers\AuditTrailObserver::class);
        \App\Models\Staff::observe(\App\Observers\AuditTrailObserver::class);
        \App\Models\Branch::observe(\App\Observers\AuditTrailObserver::class);
        \App\Models\Section::observe(\App\Observers\AuditTrailObserver::class);
        \App\Models\Role::observe(\App\Observers\AuditTrailObserver::class);
        \App\Models\Permission::observe(\App\Observers\AuditTrailObserver::class);

        foreach ([
            \App\Models\Aviation\Airport::class,
            \App\Models\Aviation\AircraftType::class,
            \App\Models\Aviation\Aircraft::class,
            \App\Models\Aviation\Grounding::class,
            \App\Models\Aviation\CrewMember::class,
            \App\Models\Aviation\Qualification::class,
            \App\Models\Aviation\CrewAbsence::class,
            \App\Models\Aviation\RestPeriod::class,
            \App\Models\Aviation\FatiguePolicy::class,
            \App\Models\Aviation\Flight::class,
            \App\Models\Aviation\Duty::class,
            \App\Models\Aviation\DutyActivity::class,
        ] as $model) $model::observe(\App\Observers\AuditTrailObserver::class);

        // ===========================
        // ✅ Register Mobile API routes
        // ===========================
        Route::middleware('api')
            ->prefix('api/mobile')
            ->group(base_path('routes/mobileApi.php'));
    }
}
