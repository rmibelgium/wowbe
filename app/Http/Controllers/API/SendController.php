<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\EcowittSendRequest;
use App\Http\Requests\API\SendRequest;
use App\Http\Requests\API\WeatherUndergroundSendRequest;
use App\Models\Observation;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SendController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Detect Ecowitt protocol
        if ($request->has('PASSKEY') === true) {
            return $this->handleEcowittRequest($request);
        }

        // Detect WeatherUnderground protocol
        if ($request->has(['ID', 'PASSWORD']) === true) {
            return $this->handleWeatherUndergroundRequest($request);
        }

        // Use default WOW protocol
        return $this->handleRequest($request);
    }

    /**
     * Handle default WOW protocol request.
     */
    private function handleRequest(Request $request): JsonResponse
    {
        // Prepare data for validation
        $data = $request->all();
        $data['dateutc'] = self::decodeDateTime($request->input('dateutc'));

        // Get validation rules
        $rules = (new SendRequest)->rules();
        $validated = Validator::make($data, $rules)->validate();

        // Check authorization
        $siteID = $request->input('siteid');
        $siteAuthenticationKey = $request->input('siteAuthenticationKey');

        if (Str::isUuid($siteID) === true) {
            /** @var Site $site */
            $site = Site::findOrFail($siteID);
        } else {
            /** @var Site $site */
            $site = Site::where('short_id', $siteID)->firstOrFail();
        }

        if (is_null($siteAuthenticationKey) || $site->auth_key !== $siteAuthenticationKey) {
            abort(403);
        }

        $observation = $this->createObservation($validated, $site);

        return response()->json($observation);
    }

    /**
     * Handle Ecowitt protocol request.
     */
    private function handleEcowittRequest(Request $request): JsonResponse
    {
        // Prepare data for validation
        $data = $request->all();
        $data['dateutc'] = self::decodeDateTime($request->input('dateutc'));

        // Get validation rules
        $rules = (new EcowittSendRequest)->rules();
        $validated = Validator::make($data, $rules)->validate();

        // Check authorization
        /** @var Site $site */
        $site = Site::where('mac_address', strtolower($validated['PASSKEY']))->firstOrFail();

        // Transform data
        $validated['softwaretype'] = $validated['stationtype'];
        $validated['baromin'] = $validated['baromrelin'] ?? null;
        $validated['absbaromin'] = $validated['baromabsin'] ?? null;
        $validated['rainin'] = $validated['rainratein'] ?? null;

        $observation = $this->createObservation($validated, $site);

        return response()->json($observation);
    }

    /**
     * Handle WeatherUnderground protocol request.
     */
    private function handleWeatherUndergroundRequest(Request $request): JsonResponse
    {
        // Prepare data for validation
        $data = $request->all();
        $data['dateutc'] = self::decodeDateTime($request->input('dateutc'));

        // Get validation rules
        $rules = (new WeatherUndergroundSendRequest)->rules();
        $validated = Validator::make($data, $rules)->validate();

        // Check authorization
        $siteID = $request->input('ID');
        $siteAuthenticationKey = $request->input('PASSWORD');

        if (Str::isUuid($siteID) === true) {
            /** @var Site $site */
            $site = Site::findOrFail($siteID);
        } else {
            /** @var Site $site */
            $site = Site::where('short_id', $siteID)->firstOrFail();
        }

        if (is_null($siteAuthenticationKey) || $site->auth_key !== $siteAuthenticationKey) {
            abort(403);
        }

        $observation = $this->createObservation($validated, $site);

        return response()->json($observation);
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

    private static function decodeDateTime(?string $source): ?string
    {
        if (is_null($source)) {
            return null;
        }

        $timezone = date_default_timezone_get();

        date_default_timezone_set('UTC');

        $source = urldecode($source);

        $datetime = strtotime($source);
        if ($datetime !== false) {
            return date('Y-m-d H:i:s', $datetime);
        }

        date_default_timezone_set($timezone);

        return $source;
    }
}
