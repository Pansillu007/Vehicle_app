<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('vehicles.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 0l7 7m0 0l-7 7"></path>
                                </svg>
                            </a>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">{{ $vehicle->name }}</h2>
                                <p class="text-gray-600 mt-1">{{ $vehicle->make }} {{ $vehicle->model }} • {{ $vehicle->number_plate }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('vehicles.services.create', $vehicle) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Service
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Manufacturer</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $vehicle->make }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-xl p-3">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Model</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $vehicle->model }}</p>
                        </div>
                        <div class="bg-green-100 rounded-xl p-3">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                @if($vehicle->year)
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Year</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $vehicle->year }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-xl p-3">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif

                @if($vehicle->mileage)
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Mileage</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($vehicle->mileage) }} km</p>
                        </div>
                        <div class="bg-orange-100 rounded-xl p-3">
                            <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Additional Details -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Registration Number</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $vehicle->number_plate }}</p>
                    </div>
                    @if($vehicle->color)
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Color</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $vehicle->color }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Added On</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $vehicle->created_at->format('M d, Y') }}</p>
                    </div>
                    @if($nextService = $vehicle->getNextServiceDue())
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-200">
                        <p class="text-sm text-amber-800 font-medium">Next Service Due</p>
                        <p class="text-lg font-bold text-amber-900 mt-1">{{ $nextService->format('M d, Y') }}</p>
                        @if($vehicle->requiresService())
                        <span class="inline-block mt-2 bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Overdue</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Service Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                    <p class="text-green-100 text-sm font-medium">Total Services</p>
                    <p class="text-4xl font-bold mt-2">{{ $vehicle->serviceRecords->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                    <p class="text-blue-100 text-sm font-medium">Total Maintenance Cost</p>
                    <p class="text-4xl font-bold mt-2">RM {{ number_format($vehicle->getTotalServiceCost(), 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <p class="text-purple-100 text-sm font-medium">Last Service</p>
                    <p class="text-2xl font-bold mt-2">
                        @if($vehicle->serviceRecords->count() > 0)
                            {{ $vehicle->serviceRecords->first()->service_date->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>

            <!-- Service History -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Service History</h3>
                        <a href="{{ route('vehicles.services.index', $vehicle) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                            View All Services
                        </a>
                    </div>

                    @if($vehicle->serviceRecords->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Provider</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cost</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($vehicle->serviceRecords->take(10) as $record)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->service_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $record->service_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($record->description, 50) ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->service_provider ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">RM {{ number_format($record->cost, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex gap-3">
                                                <a href="{{ route('vehicles.services.edit', [$vehicle, $record]) }}" class="text-yellow-600 hover:text-yellow-700 font-medium">Edit</a>
                                                <form method="POST" action="{{ route('vehicles.services.destroy', [$vehicle, $record]) }}" class="inline" onsubmit="return confirm('Delete this service record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($vehicle->serviceRecords->count() > 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('vehicles.services.index', $vehicle) }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                                    View all {{ $vehicle->serviceRecords->count() }} service records →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">No service records yet</h3>
                            <p class="mt-2 text-gray-600">Start tracking maintenance for this vehicle</p>
                            <a href="{{ route('vehicles.services.create', $vehicle) }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl shadow-lg">
                                Add First Service Record
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
