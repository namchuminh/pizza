<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BorderController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SolesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\CartController;
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

    Route::post('/sizes', [SizeController::class, 'store'])->middleware('role:manager'); 
    Route::post('/sizes/{size}', [SizeController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->middleware('role:manager'); 

    Route::post('/soles', [SolesController::class, 'store'])->middleware('role:manager'); 
    Route::post('/soles/{sole}', [SolesController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/soles/{sole}', [SolesController::class, 'destroy'])->middleware('role:manager'); 
    
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:manager'); 
    Route::post('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:manager'); 

    Route::post('/coupon', [CouponController::class, 'store'])->middleware('role:manager'); 
    Route::post('/coupon/{coupon}', [CouponController::class, 'update'])->middleware('role:manager'); 
    Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy'])->middleware('role:manager'); 

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store'])->middleware('role:customer'); 
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->middleware('role:manager'); 
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->middleware('role:manager'); 

    Route::get('/orders/{order}/detail', [DetailOrderController::class, 'show']);
    Route::post('/orders/{order}/detail', [DetailOrderController::class, 'store'])->middleware('role:customer');

    Route::get('/carts', [CartController::class, 'index'])->middleware('role:customer');
    Route::post('/carts', [CartController::class, 'store'])->middleware('role:customer');
    Route::post('/carts/{cart}', [CartController::class, 'update'])->middleware('role:customer');
    Route::delete('/carts/{cart}', [CartController::class, 'destroy'])->middleware('role:customer');

    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/{user}/block', [UserController::class, 'block']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/borders', [BorderController::class, 'index']);
Route::get('/borders/{border}', [BorderController::class, 'show']);

Route::get('/sizes', [SizeController::class, 'index']);
Route::get('/sizes/{size}', [SizeController::class, 'show']);

Route::get('/soles', [SolesController::class, 'index']);
Route::get('/soles/{soles}', [SolesController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/coupon', [CouponController::class, 'index']);
Route::get('/coupon/{coupon}', [CouponController::class, 'show']);

Route::get('/user/{user}', [UserController::class, 'show']);