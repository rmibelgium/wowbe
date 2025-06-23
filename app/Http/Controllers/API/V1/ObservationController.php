<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ObservationHelper;
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
            ->filter(fn ($site) => $site->latest->isNotEmpty());

        $result = [
            'crs' => [
                'type' => 'name',
                'properties' => [
                    'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84',
                ],
            ],
            'features' => $sites->map(function (Site $site) {
                $observation = $site->latest->first();

                return [
                    'geometry' => ObservationHelper::serializeGeometry($observation, false),
                    'properties' => [
                        'siteId' => $site->id,
                        'siteName' => $site->name,
                        'isOfficial' => false,
                        'timestamp' => ObservationHelper::serializeDateUTC($observation),
                        'primary' => [
                            'dt' => ObservationHelper::convertFarenheitToCelsius($observation->tempf),
                            'dpt' => null,
                            'dws' => ObservationHelper::convertMpHToKmH($observation->windspeedmph),
                            'dwd' => $observation->winddir,
                            'drr' => $observation->rainin,
                            'dra' => null,
                            'dap' => ObservationHelper::convertInHgToHpa($observation->baromin),
                            'dh' => $observation->humidity,
                        ],
                    ],
                ];
            }),
        ];

        return response()->json($result);
    }
}
