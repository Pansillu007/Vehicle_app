<x-app-layout>
<<<<<<< HEAD
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-2xl shadow-lg px-6 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-white">My Vehicles</h2>
                        <p class="text-blue-100 mt-2">Manage and track your vehicle fleet</p>
                    </div>
                    <a href="{{ route('vehicles.create') }}" class="bg-white text-blue-600 hover:bg-blue-50 font-bold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Vehicle
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white shadow-lg px-6 py-6">
                <form method="GET" action="{{ route('vehicles.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by name, make, model, or plate..." 
                                   class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        </div>

                        <!-- Make Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Manufacturer</label>
                            <select name="make" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                <option value="">All Makes</option>
                                @foreach($makes as $make)
                                    <option value="{{ $make }}" {{ request('make') == $make ? 'selected' : '' }}>{{ $make }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                            <select name="year" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Fuel Type Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fuel Type</label>
                            <select name="fuel_type" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                <option value="">All Types</option>
                                @foreach($fuelTypes as $fuelType)
                                    <option value="{{ $fuelType }}" {{ request('fuel_type') == $fuelType ? 'selected' : '' }}>{{ $fuelType }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="md:col-span-3 flex gap-3 items-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Apply Filters
                            </button>
                            @if(request()->hasAny(['search', 'make', 'year', 'fuel_type']))
                                <a href="{{ route('vehicles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all">
                                    Clear All
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Vehicles List -->
            <div class="bg-white shadow-lg rounded-b-2xl overflow-hidden">
                @if($vehicles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Vehicle</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Make/Model</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Year</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Plate</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fuel</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Mileage</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Services</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($vehicles as $vehicle)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $vehicle->name }}</div>
                                        @if($vehicle->color)
                                            <div class="text-xs text-gray-500 mt-1">Color: {{ $vehicle->color }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $vehicle->make }}</div>
                                        <div class="text-sm text-gray-500">{{ $vehicle->model }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $vehicle->year ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">{{ $vehicle->number_plate }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if($vehicle->fuel_type)
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">{{ $vehicle->fuel_type }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $vehicle->mileage ? number_format($vehicle->mileage) . ' km' : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-bold">{{ $vehicle->serviceRecords->count() }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($vehicle->requires_service)
                                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold animate-pulse">⚠️ Service Due</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">✓ OK</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('vehicles.show', $vehicle) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-bold transition-colors" title="View">
                                                👁️ View
                                            </a>
                                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-md text-xs font-bold transition-colors" title="Edit">
                                                ✏️ Edit
                                            </a>
                                            <a href="{{ route('vehicles.services.index', $vehicle) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-xs font-bold transition-colors" title="Services">
                                                🔧 Services
                                            </a>
                                            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-xs font-bold transition-colors" title="Delete">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $vehicles->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">No vehicles found</h3>
                        <p class="mt-2 text-gray-500">
                            @if(request()->hasAny(['search', 'make', 'year', 'fuel_type']))
                                No vehicles match your filters. Try adjusting your search criteria.
                            @else
                                Get started by adding your first vehicle to the system.
                            @endif
                        </p>
                        @if(!request()->hasAny(['search', 'make', 'year', 'fuel_type']))
                            <a href="{{ route('vehicles.create') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">
                                Add Your First Vehicle
                            </a>
                        @endif
                    </div>
                @endif
            </div>
=======
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="page-header-title">{{ __('My Vehicles') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Search, filter, and manage your fleet</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('trash.index') }}" class="btn-secondary text-sm">Trash</a>
                <a href="{{ route('vehicles.create') }}" class="btn-primary shrink-0">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Vehicle
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:vehicle-list />
>>>>>>> ec6237d (Third Week of Assignment small changes)
        </div>
    </div>
</x-app-layout>
