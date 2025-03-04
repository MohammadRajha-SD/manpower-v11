<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Setting\CurrencyController;
use App\Http\Controllers\Admin\Setting\LocalisationController;
use App\Http\Controllers\Admin\Setting\SettingController;

//** App Settings   */
Route::resource('settings', SettingController::class);

Route::prefix('setting')->group(function () {
    Route::resource('currencies', CurrencyController::class);
    Route::resource('localisation', LocalisationController::class);
});
//** App Settings --  */