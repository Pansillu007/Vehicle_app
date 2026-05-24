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

                <p data-vehicles-loading class="api-loading mb-4 hidden">Loading vehicles...</p>

                <div data-vehicles-skeleton class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
                    @for ($i = 0; $i < 3; $i++)
                    <div class="glass-card rounded-3xl overflow-hidden">
                        <div class="skeleton-card"></div>
                        <div class="p-5 space-y-3">
                            <div class="skeleton-line"></div>
                            <div class="skeleton-line-sm"></div>
                        </div>
                    </div>
                    @endfor
                </div>

                <div data-vehicles-grid class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 transition-opacity hidden"></div>

                <div data-vehicles-empty class="hidden empty-state col-span-full">
                    <div class="empty-state-icon">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">No vehicles found</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2 mb-6">Add your first vehicle to start tracking maintenance.</p>
                    <a href="{{ route('vehicles.create') }}" class="btn-primary inline-flex">Add Vehicle</a>
                </div>

                <div data-pagination class="mt-6 flex flex-wrap gap-2 justify-center"></div>
            </div>
        </div>
    </div>
</x-app-layout>
