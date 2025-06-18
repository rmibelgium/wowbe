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
}
