<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ObservationHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class ObservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        $sites = Site::query()
            ->with([
                'latest' => function ($query) use ($validated) {
                    $datetime = Date::parse($validated['date']);

                    $query
                        ->where('dateutc', '<=', $datetime)
                        ->where('dateutc', '>=', $datetime->clone()->subMinutes(10));
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
                    'geometry' => SiteHelper::serializeGeometry($site, false),
                    'properties' => [
                        'siteId' => $site->id,
                        'siteName' => $site->name,
                        'isOfficial' => false,
                        'timestamp' => ObservationHelper::serializeDateUTC($observation),
                        'primary' => [
                            'dt' => $observation->tempf,
                            'dpt' => null,
                            'dws' => $observation->windspeedmph,
                            'dwd' => $observation->winddir,
                            'drr' => $observation->rainin,
                            'dra' => null,
                            'dap' => null,
                            'dh' => $observation->humidity,
                        ],
                    ],
                ];
            }),
        ];

        return response()->json($result);
    }
}
