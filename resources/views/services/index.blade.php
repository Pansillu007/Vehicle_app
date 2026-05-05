<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Service Records</h2>
                            <p class="text-gray-600 mt-1">Vehicle: {{ $vehicle->name }} ({{ $vehicle->number_plate }})</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('vehicles.services.create', $vehicle) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Service Record
                            </a>
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Back to Vehicle
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Search and Filter -->
                    <form method="GET" action="{{ route('vehicles.services.index', $vehicle) }}" class="mb-6">
                        <div class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search services..." 
                                   class="flex-1 border border-gray-300 rounded-md px-4 py-2">
                            <input type="date" name="date" value="{{ request('date') }}" 
                                   class="border border-gray-300 rounded-md px-4 py-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Search
                            </button>
                            @if(request('search') || request('date'))
                                <a href="{{ route('vehicles.services.index', $vehicle) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>

                    @if($serviceRecords->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($serviceRecords as $record)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $record->service_type }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $record->service_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($record->description, 50) ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($record->cost, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $record->service_provider ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('vehicles.services.edit', [$vehicle, $record]) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                            <form method="POST" action="{{ route('vehicles.services.destroy', [$vehicle, $record]) }}" class="inline" onsubmit="return confirm('Delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $serviceRecords->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No service records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
