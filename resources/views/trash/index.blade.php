<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="page-header-title">Trash</h2>
            <p class="page-header-subtitle">Restore or permanently delete soft-deleted records</p>
        </div>
    </x-slot>
    <div class="page-container">
        <div id="trash-app" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Deleted Vehicles</h3>
                <div class="overflow-x-auto">
                    <table class="premium-table">
                        <thead><tr><th>Name</th><th>Deleted</th><th class="text-right">Actions</th></tr></thead>
                        <tbody>
                            @forelse($trashedVehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->name }} @if(auth()->user()->isAdmin() && $vehicle->user) <span class="text-xs text-gray-500">({{ $vehicle->user->name }})</span> @endif</td>
                                <td>{{ $vehicle->deleted_at->diffForHumans() }}</td>
                                <td class="text-right space-x-2">
                                    <button type="button" data-restore-vehicle="{{ $vehicle->id }}" class="text-blue-500 text-sm bg-transparent border-0 cursor-pointer hover:underline">Restore</button>
                                    <button type="button" data-force-delete-vehicle="{{ $vehicle->id }}" class="text-red-500 text-sm bg-transparent border-0 cursor-pointer hover:underline">Delete forever</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-8 text-center text-gray-500">No deleted vehicles.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $trashedVehicles->links() }}
            </div>
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Deleted Service Records</h3>
                <table class="premium-table">
                    <thead><tr><th>Service</th><th>Vehicle</th><th class="text-right">Actions</th></tr></thead>
                    <tbody>
                        @forelse($trashedServices as $service)
                        <tr>
                            <td>{{ $service->service_type }}</td>
                            <td>{{ $service->vehicle?->name }}</td>
                            <td class="text-right space-x-2">
                                <button type="button" data-restore-service="{{ $service->id }}" class="text-blue-500 text-sm bg-transparent border-0 cursor-pointer hover:underline">Restore</button>
                                <button type="button" data-force-delete-service="{{ $service->id }}" class="text-red-500 text-sm bg-transparent border-0 cursor-pointer hover:underline">Delete forever</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-8 text-center text-gray-500">No deleted services.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $trashedServices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
