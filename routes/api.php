<?php

<<<<<<< HEAD
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
=======
use App\Http\Controllers\Api\ApiProfileController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiVehicleController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => new UserResource($request->user()->loadCount('vehicles')));

    Route::get('/profile', [ApiProfileController::class, 'show']);
    Route::put('/profile', [ApiProfileController::class, 'update']);

    Route::apiResource('vehicles', ApiVehicleController::class)->names([
        'index' => 'api.vehicles.index',
        'store' => 'api.vehicles.store',
        'show' => 'api.vehicles.show',
        'update' => 'api.vehicles.update',
        'destroy' => 'api.vehicles.destroy',
    ]);

    Route::get('/vehicles/{vehicle}/services', [ApiServiceController::class, 'index']);
    Route::post('/vehicles/{vehicle}/services', [ApiServiceController::class, 'store']);
    Route::get('/vehicles/{vehicle}/services/{service}', [ApiServiceController::class, 'show']);
    Route::put('/vehicles/{vehicle}/services/{service}', [ApiServiceController::class, 'update']);
    Route::delete('/vehicles/{vehicle}/services/{service}', [ApiServiceController::class, 'destroy']);
>>>>>>> ec6237d (Third Week of Assignment small changes)
});
