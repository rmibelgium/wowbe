<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class APIVersionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request
     */
    public function handle(Request $request, Closure $next, string $version)
    {
        // config(['app.api.version' => $version]);

        return $next($request)->header('X-API-Version', $version);
    }
}
