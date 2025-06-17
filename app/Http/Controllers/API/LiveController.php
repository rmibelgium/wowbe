<?php

namespace App\Http\Controllers\API;

use App\Helpers\ObservationHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LiveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // DB::listen(function ($query) {
        //     Log::info(
        //         $query->sql,
        //         $query->bindings
        //     );
        // });

        $sites = Site::query()
            ->with(['user', 'observations'])
            ->get();

        $result = [
            'type' => 'FeatureCollection',
            'features' => $sites->map(function (Site $site) {
                $latest = $site->latest();

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
