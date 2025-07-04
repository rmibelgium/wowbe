<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

/**
 * Commands for the Artisan console
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:refresh-daily', function () {
    DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY daily');
})->purpose('Refresh "daily" materialized view');

/**
 * Task Scheduling
 */

// Refresh "daily" materialized view
Schedule::command('db:refresh-daily')
    ->hourly()
    ->withoutOverlapping();

// Clean deprecated conversions and files without related model.
Schedule::command('media-library:clean')
    ->twiceDaily()
    ->withoutOverlapping();
