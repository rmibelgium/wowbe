<?php

namespace App\Http\Controllers\API;

use App\Helpers\ObservationHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\Observation;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiteRequest $request): RedirectResponse
    {
        $site = new Site($request->validated());
        $site->user()->associate($request->user());
        $site->save();

        return to_route('dashboard');
    }

    /**
     * Get the details of a specific site.
     */
    public function show(Site $site): JsonResponse
    {
        $result = [
            'type' => 'Feature',
            'id' => $site->id,
            'geometry' => SiteHelper::serializeGeometry($site),
            'properties' => [
                'name' => $site->name,
                'timezone' => $site->timezone,
                'owner' => [
                    'id' => $site->user->id,
                    'name' => $site->user->name,
                ],
            ],
        ];

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteRequest $request, Site $site): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site): void {}

    /**
     * Get the latest observations for a specific site.
     */
    public function latest(Site $site): JsonResponse
    {
        $latest = $site->latest->first();

        $result = [
            'type' => 'Feature',
            'id' => $latest?->id,
            'geometry' => ObservationHelper::serializeGeometry($latest),
            'properties' => [
                'site_id' => $site->id, // Required for MapLibre (only integer is allowed for feature.id)
                'dateutc' => isset($latest) ? ObservationHelper::serializeDateUTC($latest) : null,
                'primary' => [
                    'dt' => $latest?->tempf,
                    'dws' => $latest?->windspeedmph,
                    'dwd' => $latest?->winddir,
                    'drr' => $latest?->rainin,
                    'dm' => $latest?->baromin,
                    'dh' => $latest?->humidity,
                ],
            ],
        ];

        return response()->json($result);
    }

    /**
     * Get the graph data for a specific site.
     */
    public function graph(Site $site): JsonResponse
    {
        $observations = $site->observations()
            ->whereDate('dateutc', '>', now()->utc()->subHours(24))
            ->orderBy('dateutc')
            ->get();

        $result = $observations->map(fn (Observation $observation) => [
            'timestamp' => ObservationHelper::serializeDateUTC($observation),
            'primary' => [
                'dt' => ObservationHelper::convertFarenheitToCelsius($observation->tempf),
                'dws' => ObservationHelper::convertMpHToKmH($observation->windspeedmph),
                'dwd' => $observation->winddir,
                'drr' => $observation->rainin,
                'dm' => ObservationHelper::convertInHgToHpa($observation->baromin),
                'dh' => $observation->humidity,
            ],
        ]);

        return response()->json($result);
    }
}
