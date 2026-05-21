<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\DashboardAnalyticsService;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    // Inject analytics service used to build the admin dashboard metrics.
    public function __construct(
        protected DashboardAnalyticsService $analytics
    ) {}

    // Show admin dashboard with global counts and recent user activity.
    public function index()
    {
        $analytics = $this->analytics->forUser(Auth::user());

        return view('admin.dashboard', array_merge($analytics, [
            'totalUsers' => User::count(),
            'totalAllVehicles' => Vehicle::count(),
            'totalAllServices' => ServiceRecord::count(),
            'recentUsers' => User::latest()->take(8)->get(),
        ]));
    }
}
