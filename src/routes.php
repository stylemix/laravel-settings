<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware('api')->group(function () {
    Route::get('settings', \Stylemix\Settings\SettingsController::class . '@get');
    Route::post('settings', \Stylemix\Settings\SettingsController::class . '@store');
});