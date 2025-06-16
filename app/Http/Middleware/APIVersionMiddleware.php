<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class APIVersionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $version): Response
    {
        // config(['app.api.version' => $version]);

        return $next($request)->header('X-API-Version', $version);
    }
}
