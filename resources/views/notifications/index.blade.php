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
                <p class="p-8 text-center text-gray-500">No notifications.</p>
                @endforelse
            </div>
            <div class="mt-6">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>
