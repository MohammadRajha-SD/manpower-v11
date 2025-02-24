<?php

use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\DeleteImageController;
use App\Http\Controllers\Admin\ServiceReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PackController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ProviderTypeController;
use App\Http\Controllers\Admin\TaxController;

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

Route::resource('categories', CategoryController::class);
Route::resource('packs', PackController::class);

Route::resource('addresses', AddressController::class);
Route::resource('taxes', TaxController::class);

Route::resource('services', ServiceController::class);
Route::resource('service-reviews', ServiceReviewController::class);

Route::resource('providers', ProviderController::class);
Route::resource('provider-types', ProviderTypeController::class);

Route::delete('image/{id}/delete', [DeleteImageController::class, 'deleteImageFunc'])->name('image.delete');