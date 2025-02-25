<?php

use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\DeleteImageController;
use App\Http\Controllers\Admin\ProviderScheduleController;
use App\Http\Controllers\Admin\ServiceReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PackController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ProviderTypeController;
use App\Http\Controllers\Admin\TaxController;

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

Route::resource('categories', CategoryController::class);
Route::resource('packs', PackController::class);
Route::resource('addresses', AddressController::class);
Route::resource('taxes', TaxController::class);
Route::resource('coupons', CouponController::class);
Route::resource('faqs', FaqController::class);
Route::resource('faq-categories', FaqCategoryController::class);

Route::resource('services', ServiceController::class);
Route::resource('service-reviews', ServiceReviewController::class);

Route::resource('providers', ProviderController::class);
Route::resource('provider-types', ProviderTypeController::class);
Route::resource('provider-schedules', ProviderScheduleController::class);

Route::get('profile', [ProfileController::class, 'index'])->name('user.profile');
Route::put('profile/update',  [ProfileController::class, 'update'])->name('user.profile.update');

Route::delete('image/{id}/delete', [DeleteImageController::class, 'deleteImageFunc'])->name('image.delete');