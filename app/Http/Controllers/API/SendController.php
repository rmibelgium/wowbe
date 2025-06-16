<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SendRequest;
use App\Models\Reading;
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

        $reading = new Reading($validated);
        $reading->site()->associate($site);
        $reading->save();

        return response()->json($reading);
    }
}
