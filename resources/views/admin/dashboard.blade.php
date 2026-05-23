<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="page-header-title">Admin Dashboard</h2>
            <p class="page-header-subtitle">Platform overview and user management</p>
        </div>
    </x-slot>
    <div class="page-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['label' => 'Total Users', 'value' => $totalUsers, 'gradient' => 'from-blue-600 to-indigo-600'],
                    ['label' => 'All Vehicles', 'value' => $totalAllVehicles, 'gradient' => 'from-cyan-600 to-blue-600'],
                    ['label' => 'All Services', 'value' => $totalAllServices, 'gradient' => 'from-indigo-600 to-violet-600'],
                ] as $stat)
                <div class="stat-card">
                    <div class="flex items-center gap-4">
                        <div class="stat-card-icon bg-gradient-to-br {{ $stat['gradient'] }}">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn-primary">Manage Users</a>
                <a href="{{ route('admin.vehicles.index') }}" class="btn-secondary">All Vehicles</a>
                <a href="{{ route('dashboard') }}" class="btn-secondary">User Dashboard</a>
            </div>
            <div class="glass-card rounded-3xl p-6 overflow-hidden">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Recent Users</h3>
                <div class="overflow-x-auto -mx-2">
                    <table class="premium-table min-w-full">
                        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
                        <tbody>
                            @foreach($recentUsers as $u)
                            <tr>
                                <td class="font-medium text-gray-900 dark:text-white">{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td><span class="badge-blue">{{ $u->role?->label() ?? $u->role }}</span></td>
                                <td class="whitespace-nowrap">{{ $u->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
