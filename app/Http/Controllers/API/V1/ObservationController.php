<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ObservationHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    /**
     * Display the live observations, meaning the latest observation for each site
     * in the last 10 minutes.
     */
    public function live(Request $request): JsonResponse
    {
        $sites = Site::query()
            ->with([
                'latest' => function ($query) {
                    $query->where('dateutc', '>=', now()->subMinutes(10));
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
                $latest = $site->latest->first();

                return [
                    'geometry' => SiteHelper::serializeGeometry($site, false),
                    'properties' => [
                        'siteId' => $site->id,
                        'siteName' => $site->name,
                        'isOfficial' => false,
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
