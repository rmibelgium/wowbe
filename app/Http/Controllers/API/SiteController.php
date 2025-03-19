<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\Site;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiteRequest $request)
    {
        $site = new Site($request->validated());
        $site->user()->associate($request->user());
        $site->save();

        return to_route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        return response()->json($site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        
    }

    public function latest(Site $site)
    {
        return response()->json($site->readings()->latest('dateutc')->first());
    }

    public function graph(Site $site)
    {
        $readings = $site->readings()
            // ->whereDate('dateutc', '>', now()->subHours(24))
            ->orderBy('dateutc')
            ->get();

        $result = $readings->map(fn ($reading) => [
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
