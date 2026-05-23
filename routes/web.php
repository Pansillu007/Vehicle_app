<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVehicleController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\TrashController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| VehiclePro Web UI — Blade views only (no CRUD mutations)
|--------------------------------------------------------------------------
| Fortify & Jetstream register login, register, profile, and logout routes.
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    Route::get('/vehicles', [PageController::class, 'vehiclesIndex'])->name('vehicles.index');
    Route::get('/vehicles/create', [PageController::class, 'vehiclesCreate'])->name('vehicles.create');
    Route::get('/vehicles/{vehicle}', [PageController::class, 'vehiclesShow'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', [PageController::class, 'vehiclesEdit'])->name('vehicles.edit');

    Route::get('/vehicles/{vehicle}/services/create', [PageController::class, 'servicesCreate'])->name('vehicles.services.create');
    Route::get('/vehicles/{vehicle}/services/{service}/edit', [PageController::class, 'servicesEdit'])->name('vehicles.services.edit');

    Route::get('/vehicles/{vehicle}/export', [PdfExportController::class, 'vehicleReport'])->name('vehicles.export');
    Route::get('/vehicles/{vehicle}/services/export', [PdfExportController::class, 'serviceHistory'])->name('vehicles.services.export');
    Route::get('/vehicles/{vehicle}/services/{service}/invoice', [PdfExportController::class, 'serviceInvoice'])->name('vehicles.services.invoice');

    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles.index');
    });
});
