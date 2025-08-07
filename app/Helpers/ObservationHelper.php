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

    /**
     * Calculate the Mean Sea Level Pressure (MSLP) based on the barometric pressure, temperature, and altitude.
     *
     * @param  float  $baromin  Relative barometric pressure at the site location (in inches of mercury).
     * @param  float  $tempf  Air temperature (in Fahrenheit).
     * @param  float  $altitude  Altitude (in meters).
     * @return float Mean Sea Level Pressure (MSLP) in inches of mercury.
     */
    public static function mslp(float $baromin, float $tempf, float $altitude): float
    {
        // Convert Fahrenheit to Kelvin
        $tempk = ($tempf - 32) * 5 / 9 + 273.15;

        // Calculate MSLP using the barometric formula
        return round($baromin * pow((1 - (0.0065 * $altitude) / ($tempk + 0.0065 * $altitude)), -5.257), 2);
    }
}
