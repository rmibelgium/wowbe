<?php

namespace App\Helpers;

use App\Models\Observation;

class ObservationHelper
{
    /**
     * Serialize the observation date in the right format.
     * Force to array to ensure correct serialization
     * (see `serializeDate()` function in `Observation` model).
     */
    public static function serializeDateUTC(Observation $observation): string
    {
        return $observation->toArray()['dateutc'];
    }

    /**
     * Convert the temperature to Celsius.
     */
    public static function convertFarenheitToCelsius(?float $farenheit): ?float
    {
        if (is_null($farenheit)) {
            return null;
        }

        return ($farenheit - 32) * 5 / 9;
    }

    /**
     * Convert the wind speed to kilometers per hour.
     */
    public static function convertMpHToKmH(?float $mph): ?float
    {
        if (is_null($mph)) {
            return null;
        }

        return $mph * 1.609344;
    }

    /**
     * Convert the pressure to hectopascals.
     */
    public static function convertInHgToHpa(?float $inhg): ?float
    {
        if (is_null($inhg)) {
            return null;
        }

        return 1013.25 * ($inhg / 29.92);
    }

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
