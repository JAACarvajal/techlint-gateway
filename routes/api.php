<?php

use App\Http\Controllers\{AuthController, IpAddressController, UserController};
use App\Http\Middleware\CheckBearerToken;
use Illuminate\Support\Facades\Route;

// Health check
Route::get('/health-check', function () {
    return response()->json(['message' => 'OK'], 200);
});

// User autentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware([CheckBearerToken::class])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('check', [AuthController::class, 'check'])->name('auth.check');
    });
});

// User management
Route::apiResource('users', UserController::class);

// IP management
Route::apiResource('ip-addresses', IpAddressController::class)->middleware([CheckBearerToken::class]);
