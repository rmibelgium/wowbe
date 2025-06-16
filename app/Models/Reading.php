<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reading extends Model
{
    /** @use HasFactory<\Database\Factories\ReadingFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'dateutc',
        'softwaretype',
        'baromin',
        'dailyrainin',
        'dewptf',
        'humidity',
        'rainin',
        'soilmoisture',
        'soiltempf',
        'tempf',
        'visibility',
        'winddir',
        'windspeedmph',
        'windgustdir',
        'windgustmph',
    ];

    protected $casts = [
        'dateutc' => 'datetime',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(DATE_ATOM);
    }

    /**
     * Get the site producing the reading.
     *
     * @return BelongsTo<Site,self>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id'); // @phpstan-ignore return.type
    }
}
