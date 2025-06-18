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
