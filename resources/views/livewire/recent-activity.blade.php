<div class="glass-card rounded-3xl p-5 sm:p-6 transition-all duration-500">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Timeline</h3>
        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
    </div>

    @if($activities->isEmpty())
        <div class="py-12 text-center">
            <p class="text-sm text-gray-500 dark:text-slate-400">No recent activity found.</p>
        </div>
    @else
        <div class="relative space-y-6">
            <div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-gray-100 dark:bg-slate-800"></div>
            
            @foreach($activities as $activity)
                <div class="relative flex items-start gap-4">
                    <div class="z-10 w-10 h-10 rounded-full border-4 border-white dark:border-slate-900 bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-blue-500">
                        @if(str_contains($activity->action, 'create'))
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        @elseif(str_contains($activity->action, 'update'))
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 pt-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white capitalize">{{ $activity->action }} {{ class_basename($activity->subject_type) }}</h4>
                            <span class="text-[10px] font-medium text-gray-400 uppercase tracking-tighter">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{ $activity->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
