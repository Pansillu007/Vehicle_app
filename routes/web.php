<?php

<<<<<<< HEAD
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceRecordController;
=======
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVehicleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
>>>>>>> ec6237d (Third Week of Assignment small changes)

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
<<<<<<< HEAD
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $vehiclesCount = $user->vehicles()->count();
        $servicesCount = 0;
        $totalMaintenanceCost = 0;
        
        foreach ($user->vehicles as $vehicle) {
            $servicesCount += $vehicle->serviceRecords()->count();
            $totalMaintenanceCost += $vehicle->serviceRecords()->sum('cost');
        }
        
        $recentVehicles = $user->vehicles()->latest()->take(5)->get();
        $recentServices = \App\Models\ServiceRecord::whereHas('vehicle', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('vehicle')->latest()->take(10)->get();
        
        // Upcoming maintenance reminders (services within next 30 days or mileage-based)
        $upcomingReminders = [];
        $currentDate = now();
        $thirtyDaysFromNow = now()->addDays(30);
        
        foreach ($user->vehicles as $vehicle) {
            $lastService = $vehicle->serviceRecords()
                ->where('service_type', 'Oil Change')
                ->latest()
                ->first();
            
            if ($lastService) {
                $nextServiceDate = $lastService->service_date->addMonths(3);
                if ($nextServiceDate->between($currentDate, $thirtyDaysFromNow)) {
                    $upcomingReminders[] = [
                        'vehicle' => $vehicle->name . ' (' . $vehicle->number_plate . ')',
                        'service_type' => 'Oil Change Due',
                        'due_date' => $nextServiceDate->format('M d, Y'),
                        'days_remaining' => now()->diffInDays($nextServiceDate, false),
                    ];
                }
            }
            
            // High mileage alert
            if ($vehicle->mileage && $vehicle->mileage > 10000) {
                $upcomingReminders[] = [
                    'vehicle' => $vehicle->name . ' (' . $vehicle->number_plate . ')',
                    'service_type' => 'High Mileage Alert',
                    'due_date' => 'Immediate',
                    'days_remaining' => 0,
                ];
            }
        }
        
        // Monthly service costs (last 6 months)
        $monthlyCosts = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $cost = \App\Models\ServiceRecord::whereHas('vehicle', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereYear('service_date', $month->year)
              ->whereMonth('service_date', $month->month)
              ->sum('cost');
            $monthlyCosts[] = [
                'month' => $month->format('M Y'),
                'cost' => number_format($cost, 2),
            ];
        }
        
        // Service type breakdown
        $serviceTypeBreakdown = \App\Models\ServiceRecord::whereHas('vehicle', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->selectRaw('service_type, COUNT(*) as count, SUM(cost) as total_cost')
          ->groupBy('service_type')
          ->orderByDesc('count')
          ->get();
        
        return view('dashboard', compact(
            'vehiclesCount', 
            'servicesCount', 
            'recentVehicles',
            'recentServices',
            'totalMaintenanceCost',
            'upcomingReminders',
            'monthlyCosts',
            'serviceTypeBreakdown'
        ));
    })->name('dashboard');

    Route::resource('vehicles', VehicleController::class);
    Route::resource('vehicles.services', ServiceRecordController::class);
=======
    'auth',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', [VehicleController::class, 'dashboard'])->name('dashboard');

    Route::resource('vehicles', VehicleController::class);
    Route::resource('vehicles.services', ServiceRecordController::class)->scoped();

    Route::get('/vehicles/{vehicle}/export', [PdfExportController::class, 'vehicleReport'])->name('vehicles.export');
    Route::get('/vehicles/{vehicle}/services/export', [PdfExportController::class, 'serviceHistory'])->name('vehicles.services.export');
    Route::get('/vehicles/{vehicle}/services/{service}/invoice', [PdfExportController::class, 'serviceInvoice'])->name('vehicles.services.invoice');

    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/vehicles/{id}/restore', [TrashController::class, 'restoreVehicle'])->name('trash.vehicles.restore');
    Route::delete('/trash/vehicles/{id}', [TrashController::class, 'forceDeleteVehicle'])->name('trash.vehicles.force-delete');
    Route::post('/trash/services/{id}/restore', [TrashController::class, 'restoreService'])->name('trash.services.restore');
    Route::delete('/trash/services/{id}', [TrashController::class, 'forceDeleteService'])->name('trash.services.force-delete');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles.index');
    });
>>>>>>> ec6237d (Third Week of Assignment small changes)
});
