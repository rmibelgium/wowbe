<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function readings()
    {
        return $this->hasMany(Reading::class);
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
