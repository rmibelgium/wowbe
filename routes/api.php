<?php

use App\Http\Controllers\API\LiveController;
use App\Http\Controllers\API\SendController;
use App\Http\Controllers\API\SiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) { return $request->user(); })->middleware('auth');

Route::addRoute(['GET', 'POST'], '/send', SendController::class)->name('api.send');

Route::get('live', LiveController::class)->name('api.live');

Route::prefix('site')
    ->controller(SiteController::class)
    ->group(function () {
        Route::prefix('{site}')
            ->group(function () {
                Route::get('', 'show')->name('api.site.show');
                Route::get('latest', 'latest')->name('api.site.latest');
                Route::get('daily', 'daily')->name('api.site.daily');
                Route::get('graph', 'graph')->name('api.site.graph');
            });
    });
