<div>
    <div class="glass-card rounded-3xl p-4 sm:p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="form-label">Search vehicles</label>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-input-dark" placeholder="Name, plate, make, model...">
            </div>
            <div>
                <label class="form-label">Fuel type</label>
                <select wire:model.live="fuelFilter" class="form-input-dark">
                    <option value="">All fuels</option>
                    @foreach(['Petrol','Diesel','Electric','Hybrid','Other'] as $fuel)
                        <option value="{{ $fuel }}">{{ $fuel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Sort by</label>
                <select wire:model.live="sort" class="form-input-dark">
                    <option value="latest">Latest</option>
                    <option value="name">Name</option>
                    <option value="mileage">Mileage</option>
                </select>
            </div>
        </div>
    </div>

    <div wire:loading class="livewire-loading mb-4">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        Updating results...
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($vehicles as $vehicle)
            <div class="glass-card rounded-3xl overflow-hidden hover:-translate-y-1 transition-all duration-300 group">
                <div class="aspect-video bg-slate-800/50 overflow-hidden">
                    <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start gap-2 mb-2">
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $vehicle->name }}</h3>
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-mono">{{ $vehicle->number_plate }}</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}</p>
                    @if($vehicle->fuel_type)
                        <span class="badge-blue mb-3 inline-block">{{ $vehicle->fuel_type }}</span>
                    @endif
                    @if(auth()->user()->isAdmin() && $vehicle->user)
                        <p class="text-xs text-gray-400 mb-3">Owner: {{ $vehicle->user->name }}</p>
                    @endif
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn-primary text-xs py-2 px-3">View</a>
                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn-secondary text-xs py-2 px-3">Edit</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card rounded-3xl p-12 text-center text-gray-500 dark:text-gray-400">
                <p class="text-lg font-medium">No vehicles found</p>
                <a href="{{ route('vehicles.create') }}" class="btn-primary mt-4 inline-flex">Add Vehicle</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $vehicles->links() }}</div>
</div>
