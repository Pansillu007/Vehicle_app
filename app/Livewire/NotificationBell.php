<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $open = false;

    public function markAsRead(string $id): void
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        $notification?->markAsRead();
    }

    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = Auth::user()->notifications()->take(8)->get();
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('livewire.notification-bell', compact('notifications', 'unreadCount'));
    }
}
