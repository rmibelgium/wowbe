<?php

namespace App\Http\Controllers\API;

use App\Helpers\ReadingHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\Reading;
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
     * Display the specified resource.
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

    public function latest(Site $site): JsonResponse
    {
        $latest = $site->latest()->first();

        $result = [
            'type' => 'Feature',
            'id' => $latest?->id,
            'geometry' => SiteHelper::serializeGeometry($site),
            'properties' => [
                'site_id' => $site->id, // Required for MapLibre (only integer is allowed for feature.id)
                'dateutc' => isset($latest) ? ReadingHelper::serializeDateUTC($latest) : null,
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

    public function graph(Site $site): JsonResponse
    {
        $readings = $site->readings()
            ->whereDate('dateutc', '>', now()->subHours(24))
            ->orderBy('dateutc')
            ->get();

        $result = $readings->map(fn (Reading $reading) => [
            'timestamp' => ReadingHelper::serializeDateUTC($reading),
            'primary' => [
                'dt' => $reading->tempf,
                'dws' => $reading->windspeedmph,
                'dwd' => $reading->winddir,
                'drr' => $reading->rainin,
                'dm' => $reading->baromin,
                'dh' => $reading->humidity,
            ],
        ]);

        return response()->json($result);
    }
}
