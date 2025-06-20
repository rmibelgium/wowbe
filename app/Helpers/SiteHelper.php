<?php

namespace App\Helpers;

use App\Models\Site;

class SiteHelper
{
    /**
     * Serialize the geometry of a site for GeoJSON.
     *
     * @return array{type:string,coordinates:float[]}
     */
    public static function serializeGeometry(Site $site, bool $altitude = true): array
    {
        return [
            'type' => 'Point',
            'coordinates' => $altitude === true ? [$site->longitude, $site->latitude, $site->altitude] : [$site->longitude, $site->latitude],
        ];
    }
}
