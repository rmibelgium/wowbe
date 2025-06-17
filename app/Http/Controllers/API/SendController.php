<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SendRequest;
use App\Models\Observation;
use App\Models\Site;
use Illuminate\Http\JsonResponse;

class SendController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SendRequest $request): JsonResponse
    {
        $validated = $request->validated();

        /** @var Site $site */
        $site = Site::findOrFail($validated['siteid']);

        $observation = new Observation($validated);
        $observation->site()->associate($site);
        $observation->save();

        return response()->json($observation);
    }
}
