<?php

namespace App\Models;

use App\Observers\ReadOnlyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ReadOnlyObserver::class])]
class FiveMinutesAggregateLocal extends FiveMinutesAggregate
{
    protected $table = 'observations_agg_5min_local';

    protected $casts = [
        'datelocal' => 'datetime',
        'temperature' => 'float',
        'dewpoint' => 'float',
        'humidity' => 'float',
        'pressure' => 'float',
        'windspeed' => 'float',
        'windgustspeed' => 'float',
        'winddir' => 'float',
        'windgustdir' => 'float',
        'visibility' => 'float',
        'soilmoisture' => 'float',
        'soiltemperature' => 'float',
        'dailyrain' => 'float',
        'rain' => 'float',
        'solarradiation' => 'float',
    ];
}
