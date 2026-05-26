<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $vehicle->image_url }}" alt="" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-blue-500/30 hidden sm:block">
                <div>
                    <h2 class="page-header-title">{{ $vehicle->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->number_plate }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('vehicles.export', $vehicle) }}" class="btn-secondary text-sm py-2.5">Export PDF</a>
                <a href="{{ route('vehicles.services.create', $vehicle) }}" class="btn-primary text-sm py-2.5">Add Service</a>
                <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn-secondary text-sm py-2.5">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if($vehicle->next_service_due_date || $vehicle->next_service_due_mileage)
            <div class="glass-card rounded-3xl p-5 border-l-4 border-amber-500">
                <p class="text-sm font-semibold text-amber-600 dark:text-amber-400">Service schedule</p>
                <p class="text-gray-700 dark:text-gray-300 mt-1">
                    @if($vehicle->next_service_due_date) Next due: {{ $vehicle->next_service_due_date->format('M d, Y') }} @endif
                    @if($vehicle->next_service_due_mileage) · Mileage target: {{ number_format($vehicle->next_service_due_mileage) }} @endif
                </p>
            </div>
            @endif

            <div class="glass-card rounded-3xl p-6 sm:p-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div><p class="text-sm text-gray-500">Make & Model</p><p class="font-semibold text-gray-900 dark:text-white">{{ $vehicle->make }} {{ $vehicle->model }}</p></div>
                    <div><p class="text-sm text-gray-500">Year</p><p class="font-semibold text-gray-900 dark:text-white">{{ $vehicle->year }}</p></div>
                    <div><p class="text-sm text-gray-500">Color</p><p class="font-semibold text-gray-900 dark:text-white">{{ $vehicle->color }}</p></div>
                    <div><p class="text-sm text-gray-500">Mileage</p><p class="font-semibold text-gray-900 dark:text-white">{{ number_format($vehicle->mileage) }}</p></div>
                    <div><p class="text-sm text-gray-500">Fuel</p><p class="font-semibold text-gray-900 dark:text-white">{{ $vehicle->fuel_type }}</p></div>
                    <div class="col-span-2"><p class="text-sm text-gray-500">VIN</p><p class="font-semibold font-mono text-gray-900 dark:text-white">{{ $vehicle->vin_number ?: 'N/A' }}</p></div>
                </div>
            </div>

            <div id="vehicle-services-app"
                data-vehicle-id="{{ $vehicle->id }}"
                data-routes="{{ json_encode([
                    'edit' => route('vehicles.services.edit', ['vehicle' => $vehicle->id, 'service' => '__SID__']),
                    'invoice' => route('vehicles.services.invoice', ['vehicle' => $vehicle->id, 'service' => '__SID__']),
                ]) }}">

                <div class="glass-card rounded-3xl p-4 sm:p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="form-label">Filter services</label>
                            <input type="text" data-service-search class="form-input-dark" placeholder="Type, provider, notes...">
                        </div>
                        <div>
                            <label class="form-label">Service type</label>
                            <input type="text" data-service-type class="form-input-dark" placeholder="e.g. Oil Change">
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">Total filtered cost: <span class="font-bold text-blue-500">$<span data-services-total>0.00</span></span></p>
                </div>

                <p data-services-loading class="api-loading mb-4">Loading services...</p>

                <div class="glass-card rounded-3xl overflow-hidden">
                    <div class="table-container">
                        <table class="premium-table min-w-full">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type / Provider</th>
                                    <th>Cost</th>
                                    <th>Mileage</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody data-services-body></tbody>
                        </table>
                    </div>
                    <p data-services-empty class="hidden py-12 text-center text-gray-500">No service records match your filters.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
