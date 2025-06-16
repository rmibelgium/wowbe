<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ReadingHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $sites = Site::query()
            ->with(['user', 'readings'])
            ->get();

        $result = [
            'crs' => [
                'type' => 'name',
                'properties' => [
                    'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84',
                ],
            ],
            'features' => $sites->map(function (Site $site) {
                $latest = $site->latest();

                return [
                    'geometry' => SiteHelper::serializeGeometry($site, false),
                    'properties' => [
                        'siteId' => $site->id,
                        'siteName' => $site->name,
                        'isOfficial' => false,
                        'timestamp' => isset($latest) ? ReadingHelper::serializeDateUTC($latest) : null,
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
