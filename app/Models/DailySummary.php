<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $site_id
 * @property \Illuminate\Support\Carbon $date
 * @property float $min_tempf
 * @property float $max_tempf
 * @property float $avg_tempf
 * @property float $avg_dewptf
 * @property float $avg_humidity
 * @property float $max_rain
 * @property float $max_dailyrainin
 * @property float $duration_rain
 * @property float $avg_baromin
 * @property float $max_windspeedmph
 * @property float $max_windgustmph
 */
class DailySummary extends Model
{
    protected $table = 'daily';

    public $incrementing = false;

    protected $casts = [
        'date' => 'date:Y-m-d',
        'min_tempf' => 'double',
        'max_tempf' => 'double',
        'avg_tempf' => 'double',
        'avg_dewptf' => 'double',
        'avg_humidity' => 'double',
        'max_rain' => 'double',
        'max_dailyrainin' => 'double',
        'duration_rain' => 'double',
        'avg_baromin' => 'double',
        'max_windspeedmph' => 'double',
        'max_windgustmph' => 'double',
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
