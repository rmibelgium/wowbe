<?php

namespace App\Http\Controllers\API;

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
                'fiveMinutesAggregate' => function ($query) use ($validated) {
                    $datetime = isset($validated['date']) ? Date::parse($validated['date']) : now();

                    $query
                        ->where('datetime', '<=', $datetime->utc())
                        ->where('datetime', '>=', $datetime->utc()->clone()->subMinutes(10));
                },
            ])
            ->get()
            ->filter(fn ($site) => $site->fiveMinutesAggregate->isNotEmpty())
            ->values();

        $result = [
            'type' => 'FeatureCollection',
            'features' => $sites->map(function (Site $site) {
                $latest = $site->fiveMinutesAggregate()
                    ->latest('datetime')
                    ->first();

                return [
                    'type' => 'Feature',
                    'id' => $site->id,
                    'geometry' => SiteHelper::serializeGeometry($site),
                    'properties' => [
                        'site_id' => $site->id, // Required for MapLibre (only integer is allowed for feature.id)
                        'name' => $site->name,
                        'timestamp' => $latest?->datetime->format(DATE_ATOM),
                        'primary' => [
                            'dt' => $latest?->temperature,
                            'dpt' => $latest?->dewpoint,
                            'dws' => $latest?->windspeed,
                            'dwd' => $latest?->winddir,
                            'drr' => null, // TODO
                            'dra' => $latest?->dailyrainin,
                            'dap' => $latest?->pressure,
                            'dh' => $latest?->humidity,
                        ],
                    ],
                ];
            }),
        ];

        return response()->json($result);
    }
}
