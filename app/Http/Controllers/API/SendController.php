<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SendRequest;
use Illuminate\Http\Request;

class SendController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SendRequest $request)
    {
        $validated = $request->validated();

        return response()->json($validated);
    }
}
