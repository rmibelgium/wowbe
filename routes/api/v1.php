<?php

use App\Http\Controllers\API\V1\ObservationController;
use App\Http\Controllers\API\V1\SendController;
use App\Http\Controllers\API\V1\SiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::addRoute(['GET', 'POST'], '/send', SendController::class);

Route::prefix('observation')
    ->controller(ObservationController::class)
    ->group(function () {
        Route::get('', 'index');
        // For live observations, we take the latest observation for each site in the last 10 minutes.
        Route::get('live', fn (Request $request, ObservationController $controller) => $controller->index($request->merge(['date' => now()])));
    });

Route::prefix('site')
    ->controller(SiteController::class)
    ->group(function () {
        Route::prefix('{site}')
            ->group(function () {
                Route::get('', 'show');
                Route::get('latest', 'latest');
                Route::get('daily', 'daily');
                Route::get('graph', 'graph');
            });
    });
