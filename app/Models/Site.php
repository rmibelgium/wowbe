<?php

namespace App\Models;

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

    public function authKey(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value,
            set: fn (array $value) => implode('', $value),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function readings(): HasMany
    {
        return $this->hasMany(Reading::class);
    }

    public function latest(): HasMany
    {
        return $this->readings()->latest('dateutc');
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type' => 'Feature',
            'id' => $this->id,
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $this->longitude,
                    $this->latitude,
                    $this->height,
                ],
            ],
            'properties' => [
                'name' => $this->name,
                'timezone' => $this->timezone,
                'owner' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
            ],
        ];
    }
}
