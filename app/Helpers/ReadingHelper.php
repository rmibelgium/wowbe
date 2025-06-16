<?php

namespace App\Helpers;

use App\Models\Reading;

class ReadingHelper
{
    public static function serializeDateUTC(Reading $reading): string
    {
        return $reading->toArray()['dateutc']; // Force to array to ensure correct serialization,
    }
}
