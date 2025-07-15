<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $site_id
 * @property \Illuminate\Support\Carbon $datetime
 * @property float $temperature
 * @property float $dewpoint
 * @property float $humidity
 * @property float $pressure
 * @property float $windspeed
 * @property float $windgustspeed
 * @property float $winddir
 * @property float $windgustdir
 * @property float $visibility
 * @property float $soilmoisture
 * @property float $soiltemperature
 * @property float $dailyrainin
 * @property float $rainin
 */
class FiveMinutesAggregate extends Model
{
    protected $table = 'observations_5min_agg';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'datetime' => 'date:Y-m-d H:i:s',
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
        'dailyrainin' => 'float',
        'rainin' => 'float',
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
