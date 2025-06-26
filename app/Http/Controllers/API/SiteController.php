<?php

namespace App\Http\Controllers\API;

use App\Helpers\ObservationHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Observation;
use App\Models\Site;
use Illuminate\Http\JsonResponse;

class SiteController extends Controller
{
    /**
     * Get the details of a specific site.
     */
    public function show(Site $site): JsonResponse
    {
        $result = [
            'type' => 'Feature',
            'id' => $site->id,
            'geometry' => SiteHelper::serializeGeometry($site),
            'properties' => [
                'name' => $site->name,
                'timezone' => $site->timezone,
                'owner' => [
                    'id' => $site->user->id,
                    'name' => $site->user->name,
                ],
            ],
        ];

        return response()->json($result);
    }

    /**
     * Get the latest observations for a specific site.
     */
    public function latest(Site $site): JsonResponse
    {
        $latest = $site->latest->first();

        $result = [
            'type' => 'Feature',
            'id' => $latest?->id,
            'geometry' => ObservationHelper::serializeGeometry($latest),
            'properties' => [
                'site_id' => $site->id, // Required for MapLibre (only integer is allowed for feature.id)
                'dateutc' => isset($latest) ? ObservationHelper::serializeDateUTC($latest) : null,
                'primary' => [
                    'dt' => $latest?->tempf,
                    'dws' => $latest?->windspeedmph,
                    'dwd' => $latest?->winddir,
                    'drr' => $latest?->rainin,
                    'dm' => $latest?->baromin,
                    'dh' => $latest?->humidity,
                ],
            ],
        ];

        return response()->json($result);
    }

    /**
     * Get the graph data for a specific site.
     */
    public function graph(Site $site): JsonResponse
    {
        $observations = $site->observations()
            ->whereDate('dateutc', '>', now()->utc()->subHours(24))
            ->orderBy('dateutc')
            ->get();

        $result = $observations->map(fn (Observation $observation) => [
            'timestamp' => ObservationHelper::serializeDateUTC($observation),
            'primary' => [
                'dt' => ObservationHelper::convertFarenheitToCelsius($observation->tempf),
                'dws' => ObservationHelper::convertMpHToKmH($observation->windspeedmph),
                'dwd' => $observation->winddir,
                'drr' => $observation->rainin,
                'dm' => ObservationHelper::convertInHgToHpa($observation->baromin),
                'dh' => $observation->humidity,
            ],
        ]);

        return response()->json($result);
    }
}
