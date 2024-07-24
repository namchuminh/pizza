<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('admin.login');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

