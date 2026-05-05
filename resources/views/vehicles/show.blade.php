<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Vehicle Details</h2>
                        <div class="flex gap-3">
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <a href="{{ route('vehicles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Name</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Make</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->make }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Model</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->model }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Year</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->year ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Number Plate</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->number_plate }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Color</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->color ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Mileage</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->mileage ? number_format($vehicle->mileage, 2) . ' km' : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created At</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Service Records -->
                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Service Records ({{ $vehicle->serviceRecords->count() }})</h3>
                            <div class="flex gap-3">
                                <a href="{{ route('vehicles.services.index', $vehicle) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                    View All Services
                                </a>
                                <a href="{{ route('vehicles.services.create', $vehicle) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Add Service Record
                                </a>
                            </div>
                        </div>

                        @if($vehicle->serviceRecords->count() > 0)
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
                                        @foreach($vehicle->serviceRecords->take(5) as $record)
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
                            @if($vehicle->serviceRecords->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('vehicles.services.index', $vehicle) }}" class="text-purple-600 hover:text-purple-900 font-medium">
                                        View all {{ $vehicle->serviceRecords->count() }} service records →
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 text-center py-4">No service records yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
