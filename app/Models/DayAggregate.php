<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 */
class DayAggregate extends Model
{
    protected $table = 'observations_day_agg';

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = null;

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
    ];

    /**
     * Get the site producing the observations.
     *
     * @return BelongsTo<Site,self>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id'); // @phpstan-ignore return.type
    }
}
