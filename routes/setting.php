<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Setting\CurrencyController;
use App\Http\Controllers\Admin\Setting\LocalisationController;
use App\Http\Controllers\Admin\Setting\MailConfController;
use App\Http\Controllers\Admin\Setting\PaymentController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Setting\SlideController;
use App\Http\Controllers\Admin\Setting\SocialAuthController;
use App\Http\Controllers\Admin\Setting\TermController;
use App\Http\Controllers\Admin\UserController;

//** App Settings   */
Route::resource('settings', SettingController::class);

Route::prefix('setting')->group(function () {
    Route::resource('terms', TermController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('users', UserController::class);
    Route::resource('localisation', LocalisationController::class);
    Route::resource('setting-payment', PaymentController::class);
    Route::resource('slides', SlideController::class);
    Route::resource('social-auth', SocialAuthController::class);


    //** mails : [smtp - mailgun - sparkpost] */
    Route::get('mails/smtp', [MailConfController::class, 'smtp'])->name('mails.smtp');
    Route::put('mails/smtp/{id}', [MailConfController::class, 'update_smtp'])->name('mails.smtp.update');

    Route::get('mails/mailgun', [MailConfController::class, 'mailgun'])->name('mails.mailgun');
    Route::put('mails/mailgun/{id}', [MailConfController::class, 'update_mailgun'])->name('mails.mailgun.update');

    Route::get('mails/sparkpost', [MailConfController::class, 'sparkpost'])->name('mails.sparkpost');
    Route::put('mails/sparkpost/{id}', [MailConfController::class, 'update_sparkpost'])->name('mails.sparkpost.update');
    //** mails : [smtp - mailgun - sparkpost] -- */
});
//** App Settings --  */