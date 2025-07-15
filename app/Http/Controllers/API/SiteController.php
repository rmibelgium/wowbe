<?php

namespace App\Http\Controllers\API;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\FiveMinutesAggregate;
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
        $latest = $site->fiveMinutesAggregate()
            // ->whereDate('datetime', '>', now()->utc()->subHours(24))
            ->latest('datetime')
            ->first();

        $result = [
            'type' => 'Feature',
            'id' => $latest?->id,
            'geometry' => SiteHelper::serializeGeometry($latest->site),
            'properties' => [
                'site_id' => $site->id, // Required for MapLibre (only integer is allowed for feature.id)
                'timestamp' => $latest?->datetime->format(DATE_ATOM),
                'primary' => [
                    'dt' => $latest?->temperature,
                    'dws' => $latest?->windspeed,
                    'dwd' => $latest?->winddir,
                    'drr' => $latest?->rainin,
                    'dm' => $latest?->pressure,
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
        $observations = $site->fiveMinutesAggregate()
            ->whereDate('datetime', '>', now()->utc()->subHours(24))
            ->orderBy('datetime')
            ->get();

        $result = $observations->map(fn (FiveMinutesAggregate $observation) => [
            'timestamp' => $observation->datetime->format(DATE_ATOM),
            'primary' => [
                'dt' => $observation->temperature,
                'dws' => $observation->windspeed,
                'dwd' => $observation->winddir,
                'drr' => $observation->rainin,
                'dm' => $observation->pressure,
                'dh' => $observation->humidity,
            ],
        ]);

        return response()->json($result);
    }
}
