<x-app-layout>
    <x-slot name="header"><h2 class="page-header-title">All Vehicles (Admin)</h2></x-slot>
    <div class="page-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="glass-card rounded-2xl p-4 mb-6 flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-input-dark flex-1" placeholder="Search...">
                <button type="submit" class="btn-primary">Search</button>
            </form>
            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="table-container">
                    <table class="premium-table min-w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 sm:px-6">Vehicle</th>
                                <th class="hide-on-mobile">Owner</th>
                                <th class="hide-on-mobile text-center">Plate</th>
                                <th class="text-right px-4 py-3 sm:px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                            <tr>
                                <td class="px-4 py-4 sm:px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $vehicle->name }}</span>
                                        <span class="sm:hidden text-[10px] text-gray-500">{{ $vehicle->user?->name }}</span>
                                    </div>
                                </td>
                                <td class="hide-on-mobile font-medium text-gray-600 dark:text-slate-300">{{ $vehicle->user?->name }}</td>
                                <td class="hide-on-mobile text-center font-mono text-xs">{{ $vehicle->number_plate }}</td>
                                <td class="text-right px-4 py-4 sm:px-6">
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="text-blue-500 text-xs font-bold hover:underline">View Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $vehicles->links() }}</div>
        </div>
    </div>
</x-app-layout>
