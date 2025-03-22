<?php

use App\Http\Controllers\API\SiteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('site', fn () => Inertia::render('Create'))->name('site.form');
    Route::post('site', [SiteController::class, 'store'])->name('site.store');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
