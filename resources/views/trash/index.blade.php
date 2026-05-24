<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="page-header-title">Trash</h2>
            <p class="page-header-subtitle">Restore or permanently delete soft-deleted records</p>
        </div>
    </x-slot>
    <div class="page-container">
        <div id="trash-app" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="glass-card rounded-3xl p-4 sm:p-6 overflow-hidden">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 px-2">Deleted Vehicles</h3>
                <div class="table-container">
                    <table class="premium-table min-w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 sm:px-6">Vehicle</th>
                                <th class="hide-on-mobile">Deleted</th>
                                <th class="text-right px-4 py-3 sm:px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trashedVehicles as $vehicle)
                            <tr>
                                <td class="px-4 py-4 sm:px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $vehicle->name }}</span>
                                        @if(auth()->user()->isAdmin() && $vehicle->user) 
                                            <span class="text-[10px] text-gray-500">{{ $vehicle->user->name }}</span> 
                                        @endif
                                        <span class="sm:hidden text-[10px] text-gray-500 mt-0.5">{{ $vehicle->deleted_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="hide-on-mobile">{{ $vehicle->deleted_at->diffForHumans() }}</td>
                                <td class="text-right px-4 py-4 sm:px-6 space-x-2 whitespace-nowrap">
                                    <button type="button" data-restore-vehicle="{{ $vehicle->id }}" class="text-blue-500 text-xs font-bold hover:underline">Restore</button>
                                    <button type="button" data-force-delete-vehicle="{{ $vehicle->id }}" class="text-red-500 text-xs font-bold hover:underline">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-12 text-center text-gray-500">No deleted vehicles.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-2">{{ $trashedVehicles->links() }}</div>
            </div>

            <div class="glass-card rounded-3xl p-4 sm:p-6 overflow-hidden">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 px-2">Deleted Service Records</h3>
                <div class="table-container">
                    <table class="premium-table min-w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 sm:px-6">Service Type</th>
                                <th class="hide-on-mobile">Vehicle</th>
                                <th class="text-right px-4 py-3 sm:px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trashedServices as $service)
                            <tr>
                                <td class="px-4 py-4 sm:px-6 font-bold text-gray-900 dark:text-white">
                                    {{ $service->service_type }}
                                    <span class="sm:hidden block text-[10px] font-medium text-gray-500 mt-0.5">{{ $service->vehicle?->name }}</span>
                                </td>
                                <td class="hide-on-mobile">{{ $service->vehicle?->name }}</td>
                                <td class="text-right px-4 py-4 sm:px-6 space-x-2 whitespace-nowrap">
                                    <button type="button" data-restore-service="{{ $service->id }}" class="text-blue-500 text-xs font-bold hover:underline">Restore</button>
                                    <button type="button" data-force-delete-service="{{ $service->id }}" class="text-red-500 text-xs font-bold hover:underline">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-12 text-center text-gray-500">No deleted services.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-2">{{ $trashedServices->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
