<div class="relative" x-data="{ open: @entangle('open') }">
    <button type="button" wire:click="$toggle('open')" class="relative p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 w-4 h-4 text-[10px] font-bold bg-red-500 text-white rounded-full flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 mt-2 w-80 sm:w-96 glass-card rounded-2xl shadow-2xl z-50 overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200/50 dark:border-white/10">
            <span class="font-semibold text-gray-900 dark:text-white">Notifications</span>
            @if($unreadCount > 0)
                <button type="button" wire:click="markAllRead" class="text-xs text-blue-500 hover:underline">Mark all read</button>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="px-4 py-3 border-b border-gray-100 dark:border-white/5 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-500/5' }}">
                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ $notification->data['message'] ?? 'Notification' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    @if(isset($notification->data['url']))
                        <a href="{{ $notification->data['url'] }}" class="text-xs text-blue-500 mt-1 inline-block">View</a>
                    @endif
                    @if(!$notification->read_at)
                        <button type="button" wire:click="markAsRead('{{ $notification->id }}')" class="text-xs text-gray-500 ml-2">Mark read</button>
                    @endif
                </div>
            @empty
                <p class="px-4 py-6 text-sm text-gray-500 text-center">No notifications yet.</p>
            @endforelse
        </div>
        <a href="{{ route('notifications.index') }}" class="block text-center text-xs text-blue-500 py-3 hover:bg-gray-50 dark:hover:bg-white/5">View all</a>
    </div>
</div>
