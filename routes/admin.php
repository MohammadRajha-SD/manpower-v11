<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PackController;

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

Route::delete('/admin/category/image/{id}', [CategoryController::class, 'deleteImage_'])->name('category.image.delete');
Route::resource('categories', CategoryController::class);

Route::resource('packs', PackController::class);
