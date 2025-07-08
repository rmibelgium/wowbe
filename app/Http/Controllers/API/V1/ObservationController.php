<?php

namespace App\Http\Controllers\API\V1;

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
                        ->where('datetime', '<=', $datetime)
                        ->where('datetime', '>=', $datetime->clone()->subMinutes(10));
                },
            ])
            ->get()
            ->filter(fn ($site) => $site->fiveMinutesAggregate->isNotEmpty());

        $result = [
            'crs' => [
                'type' => 'name',
                'properties' => [
                    'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84',
                ],
            ],
            'features' => $sites->map(function (Site $site) {
                $latest = $site->fiveMinutesAggregate()
                    ->latest('datetime')
                    ->first();

                return [
                    'geometry' => SiteHelper::serializeGeometry($site, false),
                    'properties' => [
                        'siteId' => $site->id,
                        'siteName' => $site->name,
                        'isOfficial' => $site->is_official,
                        'timestamp' => $latest->datetime->format(DATE_ATOM),
                        'primary' => [
                            'dt' => $latest->temperature,
                            'dpt' => null, // TODO
                            'dws' => $latest->windspeed,
                            'dwd' => $latest->winddir,
                            'drr' => null, // TODO
                            'dra' => null, // TODO
                            'dap' => $latest->pressure,
                            'dh' => $latest->humidity,
                        ],
                    ],
                ];
            }),
        ];

        return response()->json($result);
    }
}
