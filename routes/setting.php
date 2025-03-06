<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Setting\CurrencyController;
use App\Http\Controllers\Admin\Setting\LocalisationController;
use App\Http\Controllers\Admin\Setting\PaymentController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Setting\SlideController;
use App\Http\Controllers\Admin\UserController;

//** App Settings   */
Route::resource('settings', SettingController::class);

Route::prefix('setting')->group(function () {
    Route::resource('currencies', CurrencyController::class);
    Route::resource('users', UserController::class);
    Route::resource('localisation', LocalisationController::class);
    Route::resource('setting-payment', PaymentController::class);
    Route::resource('slides', SlideController::class);
});
//** App Settings --  */