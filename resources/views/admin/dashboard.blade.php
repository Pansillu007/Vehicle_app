<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">Admin Dashboard</h2>
    </x-slot>
    <div class="page-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="stat-card"><p class="text-sm text-gray-500">Total Users</p><p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p></div>
                <div class="stat-card"><p class="text-sm text-gray-500">All Vehicles</p><p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalAllVehicles }}</p></div>
                <div class="stat-card"><p class="text-sm text-gray-500">All Services</p><p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalAllServices }}</p></div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn-primary">Manage Users</a>
                <a href="{{ route('admin.vehicles.index') }}" class="btn-secondary">All Vehicles</a>
                <a href="{{ route('dashboard') }}" class="btn-secondary">User Dashboard</a>
            </div>
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Recent Users</h3>
                <div class="overflow-x-auto">
                    <table class="premium-table">
                        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
                        <tbody>
                            @foreach($recentUsers as $u)
                            <tr><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td>{{ $u->role?->label() ?? $u->role }}</td><td>{{ $u->created_at->format('M d, Y') }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
