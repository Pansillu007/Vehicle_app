<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleApiController;
use App\Http\Controllers\Api\ServiceRecordApiController;
use App\Http\Controllers\Api\ApiDocumentationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/docs', [ApiDocumentationController::class, 'index'])->name('api.docs');
    Route::apiResource('vehicles', VehicleApiController::class);
    Route::apiResource('vehicles.services', ServiceRecordApiController::class)->shallow();
});
