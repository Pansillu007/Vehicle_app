<?php

namespace App\Services;

use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardAnalyticsService
{
    public function forUser(User $user): array
    {
        $vehicleQuery = $user->isAdmin()
            ? Vehicle::query()
            : $user->vehicles();

        $vehicles = (clone $vehicleQuery)->with(['serviceRecords' => fn ($q) => $q->latest()->limit(1)])->get();
        $vehicleIds = $vehicles->pluck('id');

        $serviceQuery = ServiceRecord::query()->whereIn('vehicle_id', $vehicleIds);
        $totalServiceRecords = (clone $serviceQuery)->count();
        $maintenanceCost = (float) (clone $serviceQuery)->sum('cost');

        $monthlyCost = ServiceRecord::query()
            ->whereIn('vehicle_id', $vehicleIds)
            ->selectRaw('MONTH(service_date) as month, SUM(cost) as total')
            ->whereYear('service_date', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = (float) ($monthlyCost[$i] ?? 0);
        }

        $serviceDistribution = ServiceRecord::query()
            ->whereIn('vehicle_id', $vehicleIds)
            ->selectRaw('service_type, count(*) as total')
            ->groupBy('service_type')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'service_type')
            ->toArray();

        $fuelDistribution = $vehicles->groupBy('fuel_type')->map->count()->toArray();

        $recentActivity = ServiceRecord::query()
            ->whereIn('vehicle_id', $vehicleIds)
            ->with('vehicle')
            ->latest()
            ->take(8)
            ->get();

        $activityLogs = $user->isAdmin()
            ? \App\Models\ActivityLog::with('user')->latest()->take(10)->get()
            : $user->activityLogs()->latest()->take(10)->get();

        $reminders = $this->buildReminders($vehicles);
        $mostExpensiveVehicle = $this->mostExpensiveVehicle($vehicleIds);

        return [
            'totalVehicles' => $vehicles->count(),
            'totalServiceRecords' => $totalServiceRecords,
            'maintenanceCost' => $maintenanceCost,
            'chartData' => $chartData,
            'serviceDistribution' => $serviceDistribution,
            'fuelDistribution' => $fuelDistribution,
            'recentActivity' => $recentActivity,
            'activityLogs' => $activityLogs,
            'reminders' => $reminders,
            'mostExpensiveVehicle' => $mostExpensiveVehicle,
        ];
    }

    protected function buildReminders(Collection $vehicles): Collection
    {
        $today = Carbon::today();

        return $vehicles->map(function (Vehicle $vehicle) use ($today) {
            $dueDate = $vehicle->next_service_due_date;
            $dueMileage = $vehicle->next_service_due_mileage;
            $status = 'ok';
            $message = null;

            if ($dueDate) {
                if ($dueDate->lt($today)) {
                    $status = 'overdue';
                    $message = 'Service overdue since '.$dueDate->format('M d, Y');
                } elseif ($dueDate->lte($today->copy()->addDays(14))) {
                    $status = 'due_soon';
                    $message = 'Service due on '.$dueDate->format('M d, Y');
                }
            }

            if ($dueMileage && $vehicle->mileage >= $dueMileage) {
                $status = 'overdue';
                $message = ($message ? $message.' · ' : '').'Mileage limit reached ('.number_format($dueMileage).')';
            } elseif ($dueMileage && $vehicle->mileage >= ($dueMileage - 500)) {
                if ($status !== 'overdue') {
                    $status = 'due_soon';
                }
                $message = ($message ? $message.' · ' : '').'Approaching mileage service';
            }

            if (! $message) {
                return null;
            }

            return (object) [
                'vehicle' => $vehicle,
                'status' => $status,
                'message' => $message,
            ];
        })->filter()->values();
    }

    protected function mostExpensiveVehicle(Collection $vehicleIds): ?object
    {
        if ($vehicleIds->isEmpty()) {
            return null;
        }

        return ServiceRecord::query()
            ->whereIn('vehicle_id', $vehicleIds)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')
            ->groupBy('vehicle_id')
            ->orderByDesc('total_cost')
            ->with('vehicle')
            ->first();
    }
}
