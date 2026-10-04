<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('aviation:automate', function () {
    app(\App\Services\Aviation\AutomationService::class)->sync();
    $this->info('Maintenance schedules and pilot leave updated.');
})->purpose('Generate maintenance tasks and flight-hour recovery leave');

\Illuminate\Support\Facades\Schedule::command('aviation:automate')->everyFiveMinutes()->withoutOverlapping();
