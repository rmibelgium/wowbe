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
    public static function serializeGeometry(Site $site, bool $height = true): array
    {
        return [
            'type' => 'Point',
            'coordinates' => $height === true ? [$site->longitude, $site->latitude, $site->height] : [$site->longitude, $site->latitude],
        ];
    }
}
