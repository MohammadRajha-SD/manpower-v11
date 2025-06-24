<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AAPI\LoginController;
use App\Http\Controllers\AAPI\UserController;
use App\Http\Controllers\AAPI\CategoryController;
use App\Http\Controllers\AAPI\ForgetPasswordController;
use App\Http\Controllers\AAPI\ServiceController;

Route::prefix('app')
    ->middleware('auth:sanctum')
    ->group(function () {
        // PROFILE
        Route::post('/profile/update/{id}', [UserController::class, 'update']);
        Route::post('/user/password/update/{id}', [UserController::class, 'updatePassword']);
        Route::get('/user', [UserController::class, 'user']);
    });


Route::prefix('app')
    ->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [LoginController::class, 'register']);

        Route::get('dashboard', [CategoryController::class, 'dashboard']);
        Route::get('categories', [CategoryController::class, 'parentCategories']);
        Route::get('sub-categories/details/{id}', [CategoryController::class, 'subCategories']);
        Route::get('categories/search', [CategoryController::class, 'searchCategories']);
        Route::get('services/details/{id}', [ServiceController::class, 'index']);

        Route::post("send-reset-link-email", [ForgetPasswordController::class, 'forgotPassword']);
    });
