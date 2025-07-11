<?php

use App\Http\Controllers\API\AgreementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\MultiLoginController;

use App\Http\Controllers\API\GlobalSettingController;
use App\Http\Controllers\API\EmirateController;
use App\Http\Controllers\API\AuthProviderController;
use App\Http\Controllers\API\AuthUserController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\FaqCategoryController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\PackController;
use App\Http\Controllers\API\PartnerController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ProviderRequestController;
use App\Http\Controllers\API\ProviderTypeController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ServiceReviewController;
use App\Http\Controllers\API\SlideController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\TermController;
use App\Http\Controllers\API\UserController;


/** Mutli Login [user-admin-provider] */
Route::post('login', [MultiLoginController::class, 'login']);
/** Mutli Login [user-admin-provider] // */

/** USER STARTED HERE */
Route::prefix('user')
    ->as('user.')
    ->group(function () {
        // Auth User
        Route::post('login', [AuthUserController::class, 'login'])->name('login');
        Route::post('register', [AuthUserController::class, 'register'])->name('register');
        Route::post('forgot-password', [AuthUserController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('reset-password', [AuthUserController::class, 'resetPassword'])->name('reset-password');

        Route::middleware('auth:sanctum')->group(function () {
            // Auth User
            Route::post('address', [AuthUserController::class, 'updateAddress'])->name('updateAddress');
            Route::get('me', [AuthUserController::class, 'me'])->name('me');
            Route::post('logout', [AuthUserController::class, 'logout'])->name('logout');
            Route::put('change-password', [AuthUserController::class, 'changePassword'])->name('change-password');

            // Profile
            Route::delete('destroy', [UserController::class, 'destroy'])->name('destroy');
        });
    });
/** USER ENDED HERE */

/** PROVIDER STARTED HERE */
Route::prefix('provider')
    ->as('provider.')
    ->group(function () {
        // Auth Provider
        Route::post('login', [AuthProviderController::class, 'login'])->name('login');
        Route::post('register', [AuthProviderController::class, 'register'])->name('register');
        Route::get('register/confirm/{confirmation_code}', [AuthProviderController::class, 'confirmEmail'])->name('register.confirm');

        Route::middleware(['auth:sanctum', 'auth:provider'])->group(function () {
            // Auth Provider
            Route::get('me', [AuthProviderController::class, 'me'])->name('me');
            Route::post('logout', [AuthProviderController::class, 'logout'])->name('logout');
        });
    });
/** PROVIDER ENDED HERE */

// packs
Route::get('packs', [PackController::class, 'index']);
Route::get('partners', [PartnerController::class, 'index']);

// services
Route::get('services', [ServiceController::class, 'index']);
Route::get('search-services', [ServiceController::class, 'search']);

// faqs 
Route::get('faqs', [FaqController::class, 'index']);
Route::get('faq-categories', [FaqCategoryController::class, 'index']);

// categories
Route::get('categories', [CategoryController::class, 'index']);
Route::get('parent-categories', [CategoryController::class, 'parentCategories']);
Route::get('sub-categories/{id}', [CategoryController::class, 'subCategories']);
Route::get('sub-categories/details/{id}', [CategoryController::class, 'subCategoriesWithDetails']);

// contact-us || service-reviews
Route::post('contact-us', [ContactUsController::class, 'store']);
Route::get('service-review', [ServiceReviewController::class, 'index']);

Route::middleware(['auth:sanctum',])->group(function () {
    Route::post('/profile/add-address', [ProfileController::class, 'storeAddress'])->name('profile.add_address');
    Route::post('/profile/edit-address', [ProfileController::class, 'updateAddress'])->name('profile.edit_address');
    Route::delete('/profile/delete-address/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.delete_address');
    Route::post('/upload-profile-image', [ProfileController::class, 'updateProfileImage']);

    Route::post('service-review', [ServiceReviewController::class, 'store']);
    Route::post('check-coupon', [BookingController::class, 'checkCoupon']);
    Route::post('new-booking', [BookingController::class, 'store']);
    Route::post('cancel-booking', [BookingController::class, 'cancelBooking']);
    Route::post('booking/{booking_id}/cancel', [BookingController::class, 'cancelBooking2']);
});

Route::middleware(['auth:sanctum', 'auth:provider'])->group(function () {
    Route::post('new-subscription', [SubscriptionController::class, 'NewSubscription']);
});

Route::get('currencies', [CurrencyController::class, 'index']);
Route::get('slides', [SlideController::class, 'index']);
Route::get('emirates', [EmirateController::class, 'index']);
Route::get('provider-types', [ProviderTypeController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('profile/update', [ProfileController::class, 'store']);
});

Route::get('global-settings', [GlobalSettingController::class, 'index']);

Route::post('become-partner', [ProviderRequestController::class, 'store']);

Route::get('agreement/{uid}', [AgreementController::class, 'index']);
Route::post('agreement/{uid}', [AgreementController::class, 'store']);

Route::get('terms', [TermController::class, 'index']);

require __DIR__ . '/app.api.php';
