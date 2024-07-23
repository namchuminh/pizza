<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BorderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:manager'); 
    Route::post('/products/{product}', [ProductController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('role:manager');
    
    Route::post('/borders', [BorderController::class, 'store'])->middleware('role:manager'); 
    Route::post('/borders/{border}', [BorderController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/borders/{border}', [BorderController::class, 'destroy'])->middleware('role:manager'); 
    
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:manager'); 
    Route::post('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:manager'); 

    Route::post('/coupon', [CouponController::class, 'store'])->middleware('role:manager'); 
    Route::post('/coupon/{coupon}', [CouponController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy'])->middleware('role:manager'); 

    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/{user}/block', [UserController::class, 'block']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/borders', [BorderController::class, 'index']);
Route::get('/borders/{border}', [BorderController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/coupon', [CouponController::class, 'index']);
Route::get('/coupon/{coupon}', [CouponController::class, 'show']);

Route::get('/user/{user}', [UserController::class, 'show']);