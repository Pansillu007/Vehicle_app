<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RecentActivity extends Component
{
    protected $listeners = ['reminderAction' => '$refresh'];

    public function render()
    {
        $activities = ActivityLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('livewire.recent-activity', compact('activities'));
    }
}
