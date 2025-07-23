<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

/**
 * Commands for the Artisan console
 */
Artisan::command('db:refresh-agg-5min', function () {
    DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY observations_5min_agg;');
})->purpose('Refresh "observations_5min_agg" materialized view');

Artisan::command('db:refresh-agg-day', function () {
    DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY observations_day_agg');
})->purpose('Refresh "observations_day_agg" materialized view');

/**
 * Task Scheduling
 */
Schedule::command(\Spatie\Health\Commands\ScheduleCheckHeartbeatCommand::class)
    ->everyMinute()
    ->withoutOverlapping();

// Refresh "observations_5min_agg" materialized view
Schedule::command('db:refresh-agg-5min')
    ->everyFiveMinutes()
    ->withoutOverlapping();

// Refresh "observations_day_agg" materialized view
Schedule::command('db:refresh-agg-day')
    ->hourly()
    ->withoutOverlapping();

// Clean deprecated conversions and files without related model.
Schedule::command(\Spatie\MediaLibrary\MediaCollections\Commands\CleanCommand::class)
    ->twiceDaily()
    ->withoutOverlapping();
