<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\SizeController;
use App\Http\Controllers\Web\BorderController;
use App\Http\Controllers\Web\SolesController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ProfileController;
Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('admin.login');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::get('/category/update', [CategoryController::class, 'update'])->name('admin.category.update');

    Route::get('/size', [SizeController::class, 'index'])->name('admin.size.index');
    Route::get('/size/create', [SizeController::class, 'create'])->name('admin.size.create');
    Route::get('/size/update', [SizeController::class, 'update'])->name('admin.size.update');

    Route::get('/border', [BorderController::class, 'index'])->name('admin.border.index');
    Route::get('/border/create', [BorderController::class, 'create'])->name('admin.border.create');
    Route::get('/border/update', [BorderController::class, 'update'])->name('admin.border.update');

    Route::get('/soles', [SolesController::class, 'index'])->name('admin.soles.index');
    Route::get('/soles/create', [SolesController::class, 'create'])->name('admin.soles.create');
    Route::get('/soles/update', [SolesController::class, 'update'])->name('admin.soles.update');

    Route::get('/product', [ProductController::class, 'index'])->name('admin.product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::get('/product/update', [ProductController::class, 'update'])->name('admin.product.update');
    Route::get('/product/size', [ProductController::class, 'size'])->name('admin.product.size');
    Route::get('/product/border', [ProductController::class, 'border'])->name('admin.product.border');
    Route::get('/product/soles', [ProductController::class, 'soles'])->name('admin.product.soles');

    Route::get('/coupon', [CouponController::class, 'index'])->name('admin.coupon.index');
    Route::get('/coupon/create', [CouponController::class, 'create'])->name('admin.coupon.create');
    Route::get('/coupon/update', [CouponController::class, 'update'])->name('admin.coupon.update');

    Route::get('/customer', [UserController::class, 'index'])->name('admin.customer.index');
    Route::get('/customer/show', [UserController::class, 'show'])->name('admin.customer.show');

    Route::get('/order', [OrderController::class, 'index'])->name('admin.order.index');
    Route::get('/order/show', [OrderController::class, 'show'])->name('admin.order.show');

    Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');
});

