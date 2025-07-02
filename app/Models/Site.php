<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    /** @use HasFactory<\Database\Factories\SiteFactory> */
    use HasFactory, HasUuids, SoftDeletes;

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
        'altitude',
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
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'has_pin_code',
    ];

    protected $casts = [
        'longitude' => 'double',
        'latitude' => 'double',
        'altitude' => 'double',
    ];

    protected static function booted(): void
    {
        static::created(function (self $site) {
            event(new \App\Events\SiteCreated($site));
        });
        static::deleted(function (self $site) {
            event(new \App\Events\SiteDeleted($site));
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
     * Get the auth key attribute.
     *
     * @return Attribute<string,string>
     */
    public function authKey(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value,
            set: fn (array|string $value) => is_array($value) ? implode('', $value) : $value,
        );
    }

    /**
     * Determine if the site has a PIN code set.
     */
    public function getHasPINCodeAttribute(): bool
    {
        return preg_match('/^\d{6}$/', $this->auth_key) === 1;
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

    /**
     * Get the user that owns the site.
     *
     * @return BelongsTo<User,self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // @phpstan-ignore return.type
    }

    /**
     * Get the observations for the site.
     *
     * @return HasMany<Observation,self>
     */
    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class); // @phpstan-ignore return.type
    }

    /**
     * Get the latest observation for the site.
     *
     * @return HasMany<Observation,self>
     */
    public function latest(): HasMany
    {
        return $this->observations()
            ->latest('dateutc')
            ->limit(1);
    }

    /**
     * Get the observations daily summary for the site.
     *
     * @return HasMany<DailySummary,self>
     */
    public function daily(): HasMany
    {
        return $this->hasMany(DailySummary::class, 'site_id', 'id'); // @phpstan-ignore return.type
    }
}
