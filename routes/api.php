<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleApiController;
use App\Http\Controllers\Api\ServiceRecordApiController;
use App\Http\Controllers\Api\ApiDocumentationController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Vehicle routes
    Route::apiResource('vehicles', VehicleApiController::class);
    
    // Service record routes (nested under vehicles)
    Route::apiResource('vehicles.services', ServiceRecordApiController::class)->shallow();
    
    // API Documentation
    Route::get('/docs', [ApiDocumentationController::class, 'index'])->name('api.docs');
});
