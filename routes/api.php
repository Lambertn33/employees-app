<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->prefix('auth')->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
