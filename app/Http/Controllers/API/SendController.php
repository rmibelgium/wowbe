<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SendRequest;
use App\Http\Requests\API\WeatherUndergroundSendRequest;
use App\Models\Observation;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SendController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SendRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $site = $this->findSite($request->extractSiteID());
        $observation = $this->createObservation($validated, $site);

        return response()->json($observation);
    }

    /**
     * Handle the incoming Wunderground request.
     *
     * @see https://support.weather.com/s/article/PWS-Upload-Protocol
     */
    public function wunderground(WeatherUndergroundSendRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $site = $this->findSite($request->extractSiteID());
        $observation = $this->createObservation($validated, $site);

        return response()->json($observation);
    }

    private function findSite(string $id): Site
    {
        if (Str::isUuid($id) === true) {
            return Site::findOrFail($id);
        }

        return Site::where('short_id', $id)->firstOrFail();
    }

    /**
     * @param  array<string,mixed>  $data
     */
    private function createObservation(array $data, Site $site): Observation
    {
        $observation = new Observation($data);
        $observation->site()->associate($site);
        $observation->save();

        return $observation;
    }
}
