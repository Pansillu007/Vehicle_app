<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceRecordController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $vehiclesCount = $user->vehicles()->count();
        $servicesCount = 0;
        
        foreach ($user->vehicles as $vehicle) {
            $servicesCount += $vehicle->serviceRecords()->count();
        }
        
        $recentVehicles = $user->vehicles()->latest()->take(5)->get();
        
        return view('dashboard', compact('vehiclesCount', 'servicesCount', 'recentVehicles'));
    })->name('dashboard');

    Route::resource('vehicles', VehicleController::class);
    Route::resource('vehicles.services', ServiceRecordController::class)->shallow();
});
