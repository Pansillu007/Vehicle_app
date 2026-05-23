<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Concerns\RespondsWithApiJson;
use App\Services\DashboardAnalyticsService;
use Illuminate\Support\Facades\Auth;

class ApiDashboardController extends Controller
{
    use RespondsWithApiJson;

    public function __construct(
        protected DashboardAnalyticsService $analytics
    ) {}

    public function index()
    {
        $raw = $this->analytics->forUser(Auth::user());

        return $this->apiSuccess([
            'totalVehicles' => $raw['totalVehicles'],
            'totalServiceRecords' => $raw['totalServiceRecords'],
            'maintenanceCost' => $raw['maintenanceCost'],
            'chartData' => $raw['chartData'],
            'serviceDistribution' => $raw['serviceDistribution'],
            'fuelDistribution' => $raw['fuelDistribution'],
            'recentActivity' => collect($raw['recentActivity'])->map(fn ($activity) => [
                'id' => $activity->id,
                'service_type' => $activity->service_type,
                'cost' => (float) $activity->cost,
                'vehicle' => [
                    'id' => $activity->vehicle->id,
                    'name' => $activity->vehicle->name,
                ],
                'created_at_human' => $activity->created_at->diffForHumans(),
            ])->values(),
            'activityLogs' => collect($raw['activityLogs'])->map(fn ($log) => [
                'description' => $log->description,
                'action' => $log->action,
                'user_name' => $log->user?->name ?? 'System',
                'created_at_human' => $log->created_at->diffForHumans(),
            ])->values(),
            'reminders' => collect($raw['reminders'])->map(fn ($reminder) => [
                'vehicle_id' => $reminder->vehicle->id,
                'vehicle_name' => $reminder->vehicle->name,
                'status' => $reminder->status,
                'message' => $reminder->message,
            ])->values(),
            'mostExpensiveVehicle' => $raw['mostExpensiveVehicle'] ? [
                'name' => $raw['mostExpensiveVehicle']->vehicle?->name,
                'total_cost' => (float) ($raw['mostExpensiveVehicle']->total_cost ?? 0),
            ] : null,
        ], 'Dashboard data loaded.');
    }
}
