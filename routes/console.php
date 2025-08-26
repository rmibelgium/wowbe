<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Task Scheduling
 */

// Health checks
// Schedule::command(\Spatie\Health\Commands\ScheduleCheckHeartbeatCommand::class)
//     ->everyMinute()
//     ->withoutOverlapping();

// Clean deprecated conversions and files without related model.
// Schedule::command(\Spatie\MediaLibrary\MediaCollections\Commands\CleanCommand::class)
//     ->twiceDaily()
//     ->withoutOverlapping();
