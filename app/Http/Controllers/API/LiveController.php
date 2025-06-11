<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reading;
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
            ->with(['user', 'readings'])
            ->get();

        $features = $sites->map(function (Site $site) {
            /** @var Reading|null $last */
            $last = $site->readings->last();

            return [
                'type' => 'Feature',
                'id' => $site->id,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $site->longitude,
                        $site->latitude,
                        $site->height,
                    ],
                ],
                'properties' => [
                    'site_id' => $site->id, // Required for MapLibre (only integer is allowed for feature.id)
                    'name' => $site->name,
                    'timestamp' => $last?->dateutc,
                    'primary' => [
                        'dt' => $last?->tempf,
                        'dws' => $last?->windspeedmph,
                        'dwd' => $last?->winddir,
                        'drr' => $last?->rainin,
                        'dm' => $last?->baromin,
                        'dh' => $last?->humidity,
                    ],
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
