<x-app-layout>
    <x-slot name="header"><h2 class="page-header-title">All Vehicles (Admin)</h2></x-slot>
    <div class="page-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="glass-card rounded-2xl p-4 mb-6 flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-input-dark flex-1" placeholder="Search...">
                <button type="submit" class="btn-primary">Search</button>
            </form>
            <div class="glass-card rounded-3xl overflow-hidden">
                <table class="premium-table">
                    <thead><tr><th>Vehicle</th><th>Owner</th><th>Plate</th><th class="text-right">Actions</th></tr></thead>
                    <tbody>
                        @foreach($vehicles as $vehicle)
                        <tr>
                            <td class="font-medium text-gray-900 dark:text-white">{{ $vehicle->name }}</td>
                            <td>{{ $vehicle->user?->name }}</td>
                            <td>{{ $vehicle->number_plate }}</td>
                            <td class="text-right"><a href="{{ route('vehicles.show', $vehicle) }}" class="text-blue-500 text-sm">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $vehicles->links() }}</div>
        </div>
    </div>
</x-app-layout>
