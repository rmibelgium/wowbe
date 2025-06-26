<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('site/register', [SiteController::class, 'create'])->name('site.create');
    Route::post('site/register', [SiteController::class, 'store'])->name('site.store');

    Route::get('site/edit/{site}', [SiteController::class, 'edit'])->name('site.edit');
    Route::patch('site/edit/{site}', [SiteController::class, 'update'])->name('site.update');

    Route::get('site/delete/{site}', [SiteController::class, 'delete'])->name('site.delete');
    Route::delete('site/delete/{site}', [SiteController::class, 'destroy'])->name('site.destroy');
});
