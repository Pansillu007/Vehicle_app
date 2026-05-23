<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $stats = [
            'vehicles_count' => $user->vehicles()->count(),
            'services_count' => $user->vehicles()->withCount('serviceRecords')->get()->sum('service_records_count'),
            'total_cost' => $user->vehicles()->with('serviceRecords')->get()->sum(fn($v) => $v->totalMaintenanceCost()),
            'pending_reminders' => $user->reminders()->pending()->count(),
        ];

        return view('livewire.dashboard-stats', compact('stats'));
    }
}
