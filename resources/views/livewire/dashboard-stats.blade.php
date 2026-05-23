<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
    <div class="stat-card group">
        <div class="flex items-center gap-4">
            <div class="stat-card-icon bg-gradient-to-br from-blue-600 to-indigo-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-gray-500 dark:text-slate-400">Total Vehicles</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['vehicles_count'] }}</p>
            </div>
        </div>
    </div>
    <div class="stat-card group">
        <div class="flex items-center gap-4">
            <div class="stat-card-icon bg-gradient-to-br from-cyan-600 to-blue-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-gray-500 dark:text-slate-400">Service Records</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['services_count'] }}</p>
            </div>
        </div>
    </div>
    <div class="stat-card group">
        <div class="flex items-center gap-4">
            <div class="stat-card-icon bg-gradient-to-br from-amber-600 to-orange-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-gray-500 dark:text-slate-400">Pending Reminders</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_reminders'] }}</p>
            </div>
        </div>
    </div>
    <div class="stat-card group">
        <div class="flex items-center gap-4">
            <div class="stat-card-icon bg-gradient-to-br from-emerald-600 to-teal-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-gray-500 dark:text-slate-400">Total Spend</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['total_cost'], 2) }}</p>
            </div>
        </div>
    </div>
</div>
