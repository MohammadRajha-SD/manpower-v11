<?php

use App\Http\Controllers\Admin\DeleteImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PackController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ProviderTypeController;

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

Route::resource('categories', CategoryController::class);

Route::resource('packs', PackController::class);
Route::resource('services', ServiceController::class);
Route::resource('providers', ProviderController::class);
Route::resource('provider-types', ProviderTypeController::class);


Route::delete('image/{id}/delete', [DeleteImageController::class, 'deleteImageFunc'])->name('image.delete');
