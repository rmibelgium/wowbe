<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    /** @use HasFactory<\Database\Factories\SiteFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'timezone',
        'longitude',
        'latitude',
        'height',
        'auth_key',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'auth_key',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(DATE_ATOM);
    }

    public function authKey(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value,
            set: fn (array $value) => implode('', $value),
        );
    }

    // protected function metadata(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn() => [
    //             'created_at' => $this->created_at,
    //             'updated_at' => $this->updated_at,
    //         ],
    //     );
    // }

    protected function geometry(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'type' => 'Point',
                'coordinates' => [
                    $this->longitude,
                    $this->latitude,
                    $this->height,
                ],
            ],
        );
    }

    /**
     * Get the user that owns the site.
     *
     * @return BelongsTo<User, Site>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the readings for the site.
     *
     * @return HasMany<Reading>
     */
    public function readings(): HasMany
    {
        return $this->hasMany(Reading::class);
    }

    /**
     * Get the latest reading for the site.
     *
     * @return HasMany<Reading>
     */
    public function latest(): HasMany
    {
        return $this->readings()->latest('dateutc');
    }
}
