<?php

namespace App\Models;

use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Observation extends Model
{
    /** @use HasFactory<\Database\Factories\ObservationFactory> */
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
        'baromin' => 'double',
        'dailyrainin' => 'double',
        'dewptf' => 'double',
        'humidity' => 'double',
        'rainin' => 'double',
        'soilmoisture' => 'double',
        'soiltempf' => 'double',
        'tempf' => 'double',
        'visibility' => 'double',
        'winddir' => 'double',
        'windspeedmph' => 'double',
        'windgustdir' => 'double',
        'windgustmph' => 'double',
        'longitude' => 'double',
        'latitude' => 'double',
        'altitude' => 'double',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $observation) {
            if (isset($observation->site)) {
                $observation->longitude = $observation->site->longitude;
                $observation->latitude = $observation->site->latitude;
                $observation->altitude = $observation->site->altitude;
            }
        });
    }

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(DATE_ATOM);
    }

    /**
     * @return Attribute<DateTime,DateTime>
     */
    public function dateutc(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => new DateTime($value, new \DateTimeZone('UTC')),
        );
    }

    /**
     * Get the site producing the observation.
     *
     * @return BelongsTo<Site,self>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id'); // @phpstan-ignore return.type
    }
}
