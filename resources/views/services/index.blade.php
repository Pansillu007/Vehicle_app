<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Vehicle Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-xl mb-6 p-6 text-white">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="{{ route('vehicles.index') }}" class="text-green-100 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 0h12"></path>
                                </svg>
                            </a>
                            <h2 class="text-3xl font-bold">Service History</h2>
                        </div>
                        <p class="text-green-100 text-lg">{{ $vehicle->name }} • {{ $vehicle->make }} {{ $vehicle->model }} • {{ $vehicle->number_plate }}</p>
                    </div>
                    <a href="{{ route('vehicles.services.create', $vehicle) }}" class="bg-white hover:bg-green-50 text-green-600 font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Service Record
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-xl shadow-md p-4 mb-6">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Search & Filter -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('vehicles.services.index', $vehicle) }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Services</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by type or provider..." 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Cost</label>
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg px-4 py-2 border border-purple-200">
                                <span class="text-2xl font-bold text-purple-700">RM {{ number_format($vehicle->getTotalServiceCost(), 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Apply Filters
                        </button>
                        @if(request('search') || request('date'))
                        <a href="{{ route('vehicles.services.index', $vehicle) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Clear All
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Service Records List -->
            @if($serviceRecords->count() > 0)
            <div class="space-y-4 mb-6">
                @foreach($serviceRecords as $service)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <div class="bg-green-100 rounded-xl p-3">
                                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-xl font-bold text-gray-900">{{ $service->service_type }}</h3>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </div>
                                        <p class="text-gray-600 mb-3">{{ $service->description ?? 'No description' }}</p>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-500">Date</p>
                                                <p class="font-semibold text-gray-900">{{ $service->service_date->format('M d, Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Provider</p>
                                                <p class="font-semibold text-gray-900">{{ $service->service_provider ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Cost</p>
                                                <p class="font-bold text-green-600">RM {{ number_format($service->cost, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Recorded</p>
                                                <p class="font-semibold text-gray-900">{{ $service->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex md:flex-col gap-2">
                                <a href="{{ route('vehicles.services.edit', [$vehicle, $service]) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('vehicles.services.destroy', [$vehicle, $service]) }}" onsubmit="return confirm('Delete this service record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                {{ $serviceRecords->withQueryString()->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <svg class="h-24 w-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Service Records</h3>
                <p class="text-gray-600 mb-6">Start tracking maintenance for this vehicle.</p>
                <a href="{{ route('vehicles.services.create', $vehicle) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all inline-flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add First Service Record
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
