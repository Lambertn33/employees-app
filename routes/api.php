<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\ReportsController;

Route::controller(AuthController::class)->prefix('auth')->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('employees', EmployeesController::class);

    Route::controller(AttendancesController::class)->prefix('attendances')->group(function() {
        Route::get('/', 'index');
        Route::post('arrive', 'arrive');
        Route::post('leave', 'leave');
    });
});

        Route::controller(ReportsController::class)->prefix('reports')->group(function(){
            Route::get('/pdf', 'getPdfReports');
        });
