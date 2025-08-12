<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Locale;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    public const LOCALES = ['en', 'fr', 'nl'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set the locale based on the Accept-Language header
        $locale = Locale::acceptFromHttp($request->header('Accept-Language'));
        if ($locale !== false && in_array($locale, self::LOCALES, true)) {
            app()->setLocale($locale);
        }

        // Set the locale based on the 'lang' query parameter
        if ($request->has('lang') === true && in_array($request->query('lang'), self::LOCALES, true)) {
            app()->setLocale($request->query('lang'));
        }

        return $next($request);
    }
}
