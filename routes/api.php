<?php

// routes/api.php
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('api.version:v1')->group(base_path('routes/api/v1.php'));
Route::prefix('v2')->middleware('api.version:v2')->group(base_path('routes/api/v2.php'));
