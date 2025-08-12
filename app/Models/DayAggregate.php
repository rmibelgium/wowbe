<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $site_id
 * @property \Illuminate\Support\Carbon $date
 * @property float $min_temperature
 * @property float $max_temperature
 * @property float $avg_temperature
 * @property float $avg_dewpoint
 * @property float $avg_humidity
 * @property float $avg_pressure
 * @property float $max_windspeed
 * @property float $max_windgustspeed
 * @property float $max_dailyrainin
 * @property float $max_rainin
 * @property float $sum_rainduration
 * @property float $max_solarradiation
 * @property float $avg_solarradiation
 */
class DayAggregate extends Model
{
    protected $table = 'observations_day_agg';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'date' => 'date:Y-m-d',
        'min_temperature' => 'float',
        'max_temperature' => 'float',
        'avg_temperature' => 'float',
        'avg_dewpoint' => 'float',
        'avg_humidity' => 'float',
        'avg_pressure' => 'float',
        'max_windspeed' => 'float',
        'max_windgustspeed' => 'float',
        'max_dailyrainin' => 'float',
        'max_rainin' => 'float',
        'sum_rainduration' => 'float',
        'max_solarradiation' => 'float',
        'avg_solarradiation' => 'float',
    ];
}
