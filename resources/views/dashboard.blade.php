<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Vehicles -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Vehicles</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $vehiclesCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">Active fleet</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3">
                            <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Services -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Service Records</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $servicesCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">Total maintained</p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3">
                            <svg class="h-7 w-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Maintenance Cost -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Maintenance Cost</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">RM {{ number_format($totalMaintenanceCost, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Lifetime spend</p>
                        </div>
                        <div class="bg-purple-50 rounded-xl p-3">
                            <svg class="h-7 w-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Reminders -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pending Reminders</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ count($upcomingReminders) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Requires attention</p>
                        </div>
                        <div class="bg-orange-50 rounded-xl p-3">
                            <svg class="h-7 w-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Reminders Alert -->
            @if(count($upcomingReminders) > 0)
            <div class="bg-amber-50 border-l-4 border-amber-500 rounded-xl shadow-sm p-5">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-base font-semibold text-amber-900 mb-3">⚠️ Upcoming Maintenance Reminders</h3>
                        <div class="space-y-2">
                            @foreach($upcomingReminders as $reminder)
                            <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $reminder['vehicle'] }}</p>
                                    <p class="text-sm text-gray-600 mt-0.5">{{ $reminder['service_type'] }} • Due: {{ $reminder['due_date'] }}</p>
                                </div>
                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $reminder['days_remaining'] == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $reminder['days_remaining'] == 0 ? '🔴 Overdue' : '🟡 ' . $reminder['days_remaining'] . ' days' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Monthly Cost Chart -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Monthly Maintenance Costs
                    </h3>
                    <div class="relative h-64">
                        <canvas id="monthlyCostChart"></canvas>
                    </div>
                </div>

                <!-- Service Type Chart -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        Service Distribution
                    </h3>
                    <div class="relative h-64">
                        <canvas id="serviceTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Service Type Breakdown -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        Service Type Breakdown
                    </h3>
                    @if($serviceTypeBreakdown->count() > 0)
                    <div class="space-y-2">
                        @foreach($serviceTypeBreakdown as $service)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">{{ $service->service_type }}</p>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $service->count }} services</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-purple-600 text-sm">RM {{ number_format($service->total_cost, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 mt-3 text-sm">No service records yet</p>
                    </div>
                    @endif
                </div>

                <!-- Recent Vehicles -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Recent Vehicles
                    </h3>
                    @if($recentVehicles->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentVehicles as $vehicle)
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-blue-50 hover:shadow-md transition-all border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $vehicle->name }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">{{ $vehicle->make }} {{ $vehicle->model }} • {{ $vehicle->number_plate }}</p>
                                </div>
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <p class="text-gray-500 mt-3 text-sm">No vehicles yet</p>
                        <a href="{{ route('vehicles.create') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-700 font-medium text-sm">Add your first vehicle →</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Service History -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Recent Service History
                    </h3>
                    @if($recentServices->count() > 0)
                    <a href="{{ route('vehicles.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All →</a>
                    @endif
                </div>
                @if($recentServices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Provider</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentServices as $service)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $service->service_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $service->vehicle->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $service->service_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 hidden md:table-cell">{{ $service->service_provider ?? 'N/A' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">RM {{ number_format($service->cost, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 mt-3 text-sm">No service records yet</p>
                    <a href="{{ route('vehicles.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-700 font-medium text-sm">Add a service record →</a>
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Initialize Charts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Cost Chart Data
            const monthlyLabels = @json(collect($monthlyCosts)->pluck('month'));
            const monthlyData = @json(collect($monthlyCosts)->pluck('cost'));

            // Monthly Cost Bar Chart
            const monthlyCtx = document.getElementById('monthlyCostChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Maintenance Cost (RM)',
                        data: monthlyData.map(parseFloat),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12,
                                    family: "'Inter', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 12 },
                            callbacks: {
                                label: function(context) {
                                    return 'RM ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                font: { size: 11 },
                                callback: function(value) {
                                    return 'RM ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });

            // Service Type Chart Data
            const serviceTypes = @json($serviceTypeBreakdown->pluck('service_type'));
            const serviceCounts = @json($serviceTypeBreakdown->pluck('count'));
            const serviceColors = [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(236, 72, 153, 0.8)',
            ];

            // Service Type Doughnut Chart
            const serviceCtx = document.getElementById('serviceTypeChart').getContext('2d');
            new Chart(serviceCtx, {
                type: 'doughnut',
                data: {
                    labels: serviceTypes.length > 0 ? serviceTypes : ['No Data'],
                    datasets: [{
                        data: serviceCounts.length > 0 ? serviceCounts : [1],
                        backgroundColor: serviceCounts.length > 0 ? serviceColors : ['rgba(200, 200, 200, 0.5)'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11,
                                    family: "'Inter', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 12 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
