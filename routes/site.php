<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('site/register', [SiteController::class, 'create'])->name('site.create');
    Route::post('site/register', [SiteController::class, 'store'])->name('site.store');

    Route::get('site/{site}/edit', [SiteController::class, 'edit'])->name('site.edit');
    Route::post('site/{site}/edit', [SiteController::class, 'update'])->name('site.update'); // Using POST to allow file uploads

    Route::get('site/{site}/auth', [SiteController::class, 'editAuth'])->name('site.edit_auth');
    Route::patch('site/{site}/auth', [SiteController::class, 'updateAuth'])->name('site.update_auth');

    Route::get('site/{site}/delete', [SiteController::class, 'delete'])->name('site.delete');
    Route::delete('site/{site}/delete', [SiteController::class, 'destroy'])->name('site.destroy');
});
