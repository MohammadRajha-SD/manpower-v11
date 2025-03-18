<?php

use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\FaqCategoryController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\PackController;
use App\Http\Controllers\API\SlideController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// packs
Route::get('packs', [PackController::class, 'index']);

// faqs 
Route::get('faqs', [FaqController::class, 'index']);
Route::get('faq-categories', [FaqCategoryController::class, 'index']);

Route::get('currencies', [CurrencyController::class, 'index']);
Route::get('contact-us', [ContactUsController::class, 'index']);
Route::get('slides', [SlideController::class, 'index']);