<?php

namespace App\Helpers;

use App\Models\Observation;

class ObservationHelper
{
    public static function serializeDateUTC(Observation $observation): string
    {
        return $observation->toArray()['dateutc']; // Force to array to ensure correct serialization,
    }
}
