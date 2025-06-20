<?php

namespace App\Http\Controllers\API;

use App\Helpers\ObservationHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class ObservationController extends Controller
{
    /**
     * Get the latest observations.
     *
     * This endpoint retrieves the latest observations for all sites
     * within a 10-minutes window before the specified date.
     *
     * If no date is provided, it defaults to the **current** time.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['date'],
        ]);

        $sites = Site::query()
            ->with([
                'latest' => function ($query) use ($validated) {
                    $datetime = isset($validated['date']) ? Date::parse($validated['date']) : now();

                    $query
                        ->where('dateutc', '<=', $datetime->utc())
                        ->where('dateutc', '>=', $datetime->utc()->clone()->subMinutes(10));
                },
            ])
            ->get()
            ->filter(fn ($site) => $site->latest->isNotEmpty())
            ->values();

        $result = [
            'type' => 'FeatureCollection',
            'features' => $sites->map(function (Site $site) {
                $latest = $site->latest->first();

                return [
                    'type' => 'Feature',
                    'id' => $site->id,
                    'geometry' => SiteHelper::serializeGeometry($site),
                    'properties' => [
                        'site_id' => $site->id, // Required for MapLibre (only integer is allowed for feature.id)
                        'name' => $site->name,
                        'timestamp' => isset($latest) ? ObservationHelper::serializeDateUTC($latest) : null,
                        'primary' => [
                            'dt' => $latest?->tempf,
                            'dpt' => null,
                            'dws' => $latest?->windspeedmph,
                            'dwd' => $latest?->winddir,
                            'drr' => $latest?->rainin,
                            'dra' => null,
                            'dap' => null,
                            'dh' => $latest?->humidity,
                        ],
                    ],
                ];
            }),
        ];

        return response()->json($result);
    }
}
