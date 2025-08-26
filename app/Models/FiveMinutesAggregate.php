<?php

namespace App\Models;

use App\Observers\ReadOnlyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([ReadOnlyObserver::class])]
class FiveMinutesAggregate extends Model
{
    protected $table = 'observations_agg_5min';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'dateutc' => 'datetime',
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
        'solarradiation' => 'float',
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
