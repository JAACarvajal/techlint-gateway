<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health-check', function () {
    return response()->json(['message' => 'OK'], 200);
});
