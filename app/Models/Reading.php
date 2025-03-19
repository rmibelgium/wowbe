<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(DATE_RSS);
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type' => 'Feature',
            'id' => $this->id,
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $this->site->longitude,
                    $this->site->latitude,
                    $this->site->height,
                ],
            ],
            'properties' => [
                'dateutc' => $this->dateutc,
                'primary' => [
                    'dt' => $this->tempf,
                    'dws' => $this->windspeedmph,
                    'dwd' => $this->winddir,
                    'drr' => $this->rainin,
                    'dm' => $this->baromin,
                    'dh' => $this->humidity,
                ],
                'softwaretype' => $this->softwaretype,
                // 'dailyrainin' => $this->dailyrainin,
                // 'dewptf' => $this->dewptf,
                // 'soilmoisture' => $this->soilmoisture,
                // 'soiltempf' => $this->soiltempf,
                // 'visibility' => $this->visibility,
                // 'windgustdir' => $this->windgustdir,
                // 'windgustmph' => $this->windgustmph,
            ],
        ];
    }
}
