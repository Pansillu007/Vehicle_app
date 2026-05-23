<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpcomingReminders extends Component
{
    public function markAsComplete($id)
    {
        $reminder = Auth::user()->reminders()->find($id);
        if ($reminder) {
            $reminder->update(['status' => 'completed']);
            $this->dispatch('reminderAction');
        }
    }

    public function render()
    {
        $reminders = Auth::user()->reminders()
            ->pending()
            ->with('vehicle')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('livewire.upcoming-reminders', compact('reminders'));
    }
}
