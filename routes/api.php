<?php

use App\Http\Controllers\{AuthController, IpAddressController, UserController};
use Illuminate\Support\Facades\Route;

Route::get('/health-check', function () {
    return response()->json(['message' => 'OK'], 200);
});

Route::post('auth/login', [AuthController::class, 'login'])->middleware('audit.log')->name('auth.login');
Route::apiResource('users', UserController::class)->middleware('audit.log');

Route::middleware(['check.token', 'audit.log'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::get('check', [AuthController::class, 'check'])->name('auth.check');
    });

    Route::apiResource('ip-addresses', IpAddressController::class);
});

