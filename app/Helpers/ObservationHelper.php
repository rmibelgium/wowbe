<?php

namespace App\Helpers;

use App\Models\Observation;

class ObservationHelper
{
    /**
     * Serialize the geometry of a site for GeoJSON.
     *
     * @return array{type:string,coordinates:float[]}
     */
    public static function serializeGeometry(Observation $observation, bool $altitude = true): array
    {
        return [
            'type' => 'Point',
            'coordinates' => $altitude === true ? [$observation->longitude, $observation->latitude, $observation->altitude] : [$observation->longitude, $observation->latitude],
        ];
    }
}
