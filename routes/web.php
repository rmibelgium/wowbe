<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::any('/', fn () => redirect()->route('dashboard'));

Route::get('health', HealthCheckResultsController::class);

Route::prefix('web')
    ->group(function () {
        Route::any('/', fn () => redirect()->route('dashboard'));

        Route::get('/map', fn () => Inertia::render('Map'))->name('map');

        Route::middleware(['auth', 'verified'])
            ->group(function () {
                Route::get('dashboard', DashboardController::class)->name('dashboard');
            });

        require __DIR__.'/web/auth.php';
        require __DIR__.'/web/settings.php';
        require __DIR__.'/web/site.php';
    });

require __DIR__.'/auth.php'; // Socialite
