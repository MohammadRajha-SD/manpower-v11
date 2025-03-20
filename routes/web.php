<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;

Route::get('/', function (){
    return view('auth.login');
});

Route::middleware(['web'])->group(function () {
    Route::get('/locale/{lang}', [LocaleController::class, 'setLocale']);
});

require __DIR__ . '/auth.php';


Route::fallback(function () {
    return redirect()->back();
});
