<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="page-header-title">{{ __('Dashboard') }}</h2>
                <p class="page-header-subtitle">Fleet analytics loaded via API</p>
            </div>
            <p class="text-xs text-gray-400 dark:text-slate-500 font-medium">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </x-slot>

    <div class="page-container">
        <div id="dashboard-app"
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8 transition-opacity"
            data-user-name="{{ auth()->user()->name }}"
            data-routes="{{ json_encode([
                'vehicles' => route('vehicles.index'),
                'vehicleShow' => route('vehicles.show', ['vehicle' => '__ID__']),
            ]) }}">

            <div class="dashboard-welcome">
                <div class="dashboard-welcome-glow"></div>
                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-1">Welcome back</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white" data-user-name>{{ auth()->user()->name }}</h3>
                        <p class="text-gray-500 dark:text-slate-400 mt-2 max-w-lg text-sm">
                            You have <span class="font-semibold text-gray-900 dark:text-white" data-count-vehicles>—</span> vehicles
                            and <span class="font-semibold text-gray-900 dark:text-white" data-count-services>—</span> service records on file.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <a href="{{ route('vehicles.create') }}" class="btn-primary text-sm py-2.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Vehicle
                        </a>
                        <a href="{{ route('vehicles.index') }}" class="btn-secondary text-sm py-2.5">View Fleet</a>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 sm:gap-4">
                <a href="{{ route('vehicles.index') }}" class="quick-action flex-1 sm:flex-none">
                    <span class="quick-action-icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></span>
                    <span class="text-xs font-semibold">Vehicles</span>
                </a>
                <a href="{{ route('vehicles.create') }}" class="quick-action flex-1 sm:flex-none">
                    <span class="quick-action-icon bg-gradient-to-br from-cyan-600 to-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                    <span class="text-xs font-semibold">New Vehicle</span>
                </a>
                <a href="{{ route('trash.index') }}" class="quick-action flex-1 sm:flex-none">
                    <span class="quick-action-icon bg-gradient-to-br from-slate-600 to-slate-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></span>
                    <span class="text-xs font-semibold">Trash</span>
                </a>
                <a href="{{ route('profile.show') }}" class="quick-action flex-1 sm:flex-none">
                    <span class="quick-action-icon bg-gradient-to-br from-indigo-600 to-violet-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                    <span class="text-xs font-semibold">Profile</span>
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="quick-action flex-1 sm:flex-none">
                    <span class="quick-action-icon bg-gradient-to-br from-violet-600 to-purple-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                    <span class="text-xs font-semibold">Admin</span>
                </a>
                @endif
            </div>

            <div class="hidden space-y-3" data-reminders>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Service alerts</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6" data-dashboard-stats>
                <div class="stat-card group">
                    <div class="flex items-center gap-4">
                        <div class="stat-card-icon bg-gradient-to-br from-blue-600 to-indigo-600">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm text-gray-500 dark:text-slate-400">Total Vehicles</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white" data-stat-vehicles>—</p>
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
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white" data-stat-services>—</p>
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
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white" data-stat-reminders>—</p>
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
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white" data-stat-spend>—</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass-card rounded-3xl p-5 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Monthly Maintenance Cost</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mb-4">{{ date('Y') }} · Year to date</p>
                    <div class="relative h-56 sm:h-64">
                        <canvas id="costChart"></canvas>
                    </div>
                </div>
                <div class="glass-card rounded-3xl p-5 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Service Types</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mb-4">Distribution by category</p>
                    <div class="relative h-56 sm:h-64 flex items-center justify-center">
                        <p class="text-sm text-gray-500" data-service-chart-empty>No service data yet.</p>
                        <canvas id="serviceChart" class="max-h-full hidden"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="glass-card rounded-3xl p-5 sm:p-6" data-upcoming-reminders>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Maintenance</h3>
                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full" data-upcoming-count>0 Pending</span>
                    </div>
                    <div class="space-y-3" data-upcoming-list>
                        <p class="text-sm text-gray-500 dark:text-slate-400 py-8 text-center">Loading reminders...</p>
                    </div>
                </div>

                <div class="lg:col-span-2 glass-card rounded-3xl p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Services</h3>
                        <a href="{{ route('vehicles.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-400">View all →</a>
                    </div>
                    <div class="space-y-4 max-h-64 overflow-y-auto pr-1" data-recent-services>
                        <p class="text-sm text-gray-500 py-6 text-center">Loading dashboard data...</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-5 sm:p-6 transition-all duration-500" data-activity-section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Timeline</h3>
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                </div>
                <div class="relative space-y-6" data-activity-logs>
                    <p class="text-sm text-gray-500 dark:text-slate-400 py-6 text-center">Loading activity...</p>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @endpush
</x-app-layout>
