<?php

use App\Http\Controllers\API\ObservationController;
use App\Http\Controllers\API\SendController;
use App\Http\Controllers\API\SiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::addRoute(['GET', 'POST'], '/send', SendController::class)->name('api.send');
Route::addRoute(['GET', 'POST'], '/send/wunderground', [SendController::class, 'wunderground'])->name('api.send.wunderground');

Route::prefix('observation')
    ->controller(ObservationController::class)
    ->group(function () {
        Route::get('', 'index')->name('api.observation');
    });

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
