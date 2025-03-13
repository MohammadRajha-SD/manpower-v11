<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\DeleteImageController;
use App\Http\Controllers\Admin\ProviderScheduleController;
use App\Http\Controllers\Admin\ProviderStatisticController;
use App\Http\Controllers\Admin\ServiceReviewController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController;
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
use App\Http\Controllers\Admin\BookingStatusController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PaymentStatusController;
use App\Http\Controllers\Admin\ProviderPayoutController;
use App\Http\Controllers\Admin\ProviderRequestController;
use App\Http\Controllers\Admin\SubscriptionController;

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::resource('notifications', NotificationController::class);
Route::resource('contact-us', ContactUsController::class);

Route::resource('categories', CategoryController::class);
Route::resource('packs', PackController::class);
Route::resource('addresses', AddressController::class);
Route::resource('taxes', TaxController::class);
Route::resource('coupons', CouponController::class);
Route::resource('faqs', FaqController::class);
Route::resource('faq-categories', FaqCategoryController::class);

Route::resource('services', ServiceController::class);
Route::resource('service-reviews', ServiceReviewController::class);

Route::resource('bookings', BookingController::class);
Route::resource('booking-statuses', BookingStatusController::class);

Route::get('payments/by-month', [PaymentController::class, 'byMonth'])->name('payments.byMonth');
Route::resource('payments', PaymentController::class);
Route::resource('payment-statuses', PaymentStatusController::class);
Route::resource('payment-methods', PaymentMethodController::class);
Route::resource('provider-payouts', ProviderPayoutController::class);

/** Subscriptions  */
Route::post('/subscriptions/send-payment-email', [SubscriptionController::class, 'sendPaymentEmail'])->name('subscriptions.send-payment-email');
Route::post('/subscriptions/generate-payment-link/{id}', [SubscriptionController::class, 'generatePaymentLink'])->name('subscriptions.generate-payment-link');
Route::post('/subscriptions/create-payment-link/{id}', [SubscriptionController::class, 'createPaymentLink'])->name('subscriptions.create-payment-link');
Route::get('/subscriptions/success/{subscription}', [SubscriptionController::class, 'paymentSuccess'])->name('subscriptions.success');
Route::get('/subscriptions/cancel/{subscription}', [SubscriptionController::class, 'paymentCancel'])->name('subscriptions.cancel');
Route::resource('subscriptions', SubscriptionController::class);
/** Subscriptions // */

Route::resource('providers', ProviderController::class);
Route::resource('provider-types', ProviderTypeController::class);
Route::resource('provider-schedules', ProviderScheduleController::class);
Route::resource('provider-statistics', ProviderStatisticController::class);
Route::resource('provider-requests', ProviderRequestController::class);

Route::get('profile', [ProfileController::class, 'index'])->name('user.profile');
Route::put('profile/update', [ProfileController::class, 'update'])->name('user.profile.update');

Route::delete('image/{id}/delete', [DeleteImageController::class, 'deleteImageFunc'])->name('image.delete');

require __DIR__ . '/setting.php';