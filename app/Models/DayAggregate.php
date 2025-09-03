<?php

namespace App\Models;

use App\Observers\ReadOnlyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ReadOnlyObserver::class])]
class DayAggregate extends Model
{
    protected $table = 'observations_agg_day';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
        'min_temperature' => 'float',
        'max_temperature' => 'float',
        'avg_temperature' => 'float',
        'avg_dewpoint' => 'float',
        'avg_humidity' => 'float',
        'avg_pressure' => 'float',
        'max_windspeed' => 'float',
        'max_windgustspeed' => 'float',
        'max_dailyrain' => 'float',
        'max_rain' => 'float',
        'sum_rainduration' => 'float',
        'max_solarradiation' => 'float',
        'avg_solarradiation' => 'float',
    ];
}
