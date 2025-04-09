<?php

use App\Http\Controllers\API\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;

Route::get('/', function (){
    return view('auth.login');
});

Route::middleware(['web'])->group(function () {
    Route::get('/locale/{lang}', [LocaleController::class, 'setLocale']);
});


Route::get('/booking/payment/success', [BookingController::class, 'success'])->name('booking.payment.success');
Route::get('/booking/payment/cancel', [BookingController::class, 'cancel'])->name('booking.payment.cancel');

require __DIR__ . '/auth.php';


Route::fallback(function () {
    return redirect()->back();
});
