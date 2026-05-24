<x-app-layout>
    <x-slot name="header"><h2 class="page-header-title">Notifications</h2></x-slot>
    <div class="page-container">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(auth()->user()->unreadNotifications->count())
            <form action="{{ route('notifications.read-all') }}" method="POST" class="mb-4">@csrf<button type="submit" class="btn-secondary text-sm">Mark all as read</button></form>
            @endif
            <div class="glass-card rounded-3xl divide-y divide-gray-200/50 dark:divide-white/5">
                @forelse($notifications as $notification)
                <div class="p-4 {{ $notification->read_at ? '' : 'bg-blue-500/5' }}">
                    <p class="text-gray-900 dark:text-white">{{ $notification->data['message'] ?? 'Notification' }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    @if(isset($notification->data['url']))
                    <a href="{{ $notification->data['url'] }}" class="text-sm text-blue-500 mt-2 inline-block">Open</a>
                    @endif
                </div>
                @empty
                <div class="empty-state border-0 shadow-none bg-transparent">
                    <div class="empty-state-icon">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">No notifications yet</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2">Service reminders and alerts will appear here.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-6">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>
