<?php

use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\Api\ApiDocumentationController;
use App\Http\Controllers\Api\ApiExportController;
use App\Http\Controllers\Api\ApiProfileController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiTrashController;
use App\Http\Controllers\Api\ApiVehicleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;







/*
|--------------------------------------------------------------------------
| VehiclePro JSON API — all CRUD and data mutations (Sanctum Bearer tokens)
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/auth/google', [AuthController::class, 'googleLogin']);
});

Route::get('/docs', [ApiDocumentationController::class, 'index'])->name('api.docs');

Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'User loaded.',
            'data' => (new UserResource($request->user()->loadCount('vehicles')))->resolve(),
        ]);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard/stats', [ApiDashboardController::class, 'stats']);
    Route::get('/dashboard', [ApiDashboardController::class, 'index'])->name('api.dashboard');
    

    Route::get('/export/vehicles', [ApiExportController::class, 'vehicles'])->name('api.export.vehicles');
    Route::get('/export/vehicles/{vehicle}/services', [ApiExportController::class, 'vehicleServices'])->name('api.export.vehicle-services');

    Route::get('/profile', [ApiProfileController::class, 'show']);
    Route::put('/profile', [ApiProfileController::class, 'update']);
    Route::put('/profile/password', [ApiProfileController::class, 'updatePassword']);

    Route::get('/trash', [ApiTrashController::class, 'index'])->name('api.trash.index');
    Route::post('/trash/vehicles/{id}/restore', [ApiTrashController::class, 'restoreVehicle'])->name('api.trash.vehicles.restore');
    Route::delete('/trash/vehicles/{id}', [ApiTrashController::class, 'forceDeleteVehicle'])->name('api.trash.vehicles.force-delete');
    Route::post('/trash/services/{id}/restore', [ApiTrashController::class, 'restoreService'])->name('api.trash.services.restore');
    Route::delete('/trash/services/{id}', [ApiTrashController::class, 'forceDeleteService'])->name('api.trash.services.force-delete');

    Route::apiResource('vehicles', ApiVehicleController::class)->names([
        'index' => 'api.vehicles.index',
        'store' => 'api.vehicles.store',
        'show' => 'api.vehicles.show',
        'update' => 'api.vehicles.update',
        'destroy' => 'api.vehicles.destroy',
    ]);

    Route::apiResource('vehicles.services', ApiServiceController::class)->names([
        'index' => 'api.vehicles.services.index',
        'store' => 'api.vehicles.services.store',
        'show' => 'api.vehicles.services.show',
        'update' => 'api.vehicles.services.update',
        'destroy' => 'api.vehicles.services.destroy',
    ]);
});
