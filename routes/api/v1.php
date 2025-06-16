<?php

use App\Http\Controllers\API\V1\LiveController;
use App\Http\Controllers\API\V1\SendController;
use App\Http\Controllers\API\V1\SiteController;
use Illuminate\Support\Facades\Route;

// Route::addRoute(['GET', 'POST'], '/send', SendController::class);

Route::get('live', LiveController::class);

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
