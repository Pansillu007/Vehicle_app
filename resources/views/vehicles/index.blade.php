<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="page-header-title">{{ __('My Vehicles') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Search, filter, and manage your fleet via API</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" data-export-vehicles-csv class="btn-secondary text-sm">Export CSV</button>
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
            <div id="vehicles-app"
                data-is-admin="{{ auth()->user()->isAdmin() ? '1' : '0' }}"
                data-routes="{{ json_encode([
                    'show' => route('vehicles.show', ['vehicle' => '__ID__']),
                    'edit' => route('vehicles.edit', ['vehicle' => '__ID__']),
                ]) }}">

                <div class="glass-card rounded-3xl p-4 sm:p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label">Search vehicles</label>
                            <input type="text" data-search class="form-input-dark" placeholder="Name, plate, make, model...">
                        </div>
                        <div>
                            <label class="form-label">Fuel type</label>
                            <select data-fuel-filter class="form-input-dark">
                                <option value="">All fuels</option>
                                @foreach(['Petrol','Diesel','Electric','Hybrid','Other'] as $fuel)
                                    <option value="{{ $fuel }}">{{ $fuel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Sort by</label>
                            <select data-sort class="form-input-dark">
                                <option value="latest">Latest</option>
                                <option value="name">Name</option>
                                <option value="mileage">Mileage</option>
                            </select>
                        </div>
                    </div>
                </div>

                <p data-vehicles-loading class="livewire-loading mb-4 hidden">Loading vehicles...</p>

                <div data-vehicles-grid class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 transition-opacity"></div>

                <div data-vehicles-empty class="hidden glass-card rounded-3xl p-12 text-center text-gray-500 dark:text-gray-400 col-span-full">
                    <p class="text-lg font-medium">No vehicles found</p>
                    <a href="{{ route('vehicles.create') }}" class="btn-primary mt-4 inline-flex">Add Vehicle</a>
                </div>

                <div data-pagination class="mt-6 flex flex-wrap gap-2 justify-center"></div>
            </div>
        </div>
    </div>
</x-app-layout>
