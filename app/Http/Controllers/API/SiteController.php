<?php

namespace App\Http\Controllers\API;

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
        return response()->json($site);
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
        return response()->json($site->latest()->first());
    }

    public function graph(Site $site): JsonResponse
    {
        $readings = $site->readings()
            // ->whereDate('dateutc', '>', now()->subHours(24))
            ->orderBy('dateutc')
            ->get();

        $result = $readings->map(fn (Reading $reading) => [
            'timestamp' => $reading->dateutc,
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
