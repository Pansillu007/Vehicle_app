<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">{{ __('Edit Vehicle') }}: {{ $vehicle->name }}</h2>
    </x-slot>

    <div class="page-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-6 sm:p-8">
                <form id="vehicle-api-form" class="space-y-6"
                    data-vehicle-id="{{ $vehicle->id }}"
                    data-redirect-url="{{ route('vehicles.index') }}">
                    <div class="flex items-center gap-4 mb-2">
                        <img src="{{ $vehicle->image_url }}" alt="" class="w-24 h-24 rounded-2xl object-cover" id="vehicle-image-preview">
                        <div>
                            <label for="image" class="form-label">Update image</label>
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-input-dark">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label">Vehicle Name</label>
                            <input type="text" name="name" id="name" value="{{ $vehicle->name }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="number_plate" class="form-label">Number Plate</label>
                            <input type="text" name="number_plate" id="number_plate" value="{{ $vehicle->number_plate }}" required class="form-input-dark uppercase">
                        </div>
                        <div>
                            <label for="make" class="form-label">Make</label>
                            <input type="text" name="make" id="make" value="{{ $vehicle->make }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="model" class="form-label">Model</label>
                            <input type="text" name="model" id="model" value="{{ $vehicle->model }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="year" class="form-label">Year</label>
                            <input type="number" name="year" id="year" value="{{ $vehicle->year }}" required min="1900" max="{{ date('Y') + 1 }}" class="form-input-dark">
                        </div>
                        <div>
                            <label for="color" class="form-label">Color</label>
                            <input type="text" name="color" id="color" value="{{ $vehicle->color }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="mileage" class="form-label">Current Mileage</label>
                            <input type="number" name="mileage" id="mileage" value="{{ $vehicle->mileage }}" required min="0" step="1" class="form-input-dark">
                        </div>
                        <div>
                            <label for="fuel_type" class="form-label">Fuel Type</label>
                            <select name="fuel_type" id="fuel_type" required class="form-input-dark">
                                @foreach(['Petrol','Diesel','Electric','Hybrid','Other'] as $fuel)
                                    <option value="{{ $fuel }}" {{ $vehicle->fuel_type == $fuel ? 'selected' : '' }}>{{ $fuel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="vin_number" class="form-label">VIN Number (Optional)</label>
                            <input type="text" name="vin_number" id="vin_number" value="{{ $vehicle->vin_number }}" class="form-input-dark uppercase">
                        </div>
                        <div>
                            <label for="next_service_due_date" class="form-label">Next service due date</label>
                            <input type="date" name="next_service_due_date" id="next_service_due_date" value="{{ $vehicle->next_service_due_date?->format('Y-m-d') }}" class="form-input-dark">
                        </div>
                        <div>
                            <label for="next_service_due_mileage" class="form-label">Next service due mileage</label>
                            <input type="number" name="next_service_due_mileage" id="next_service_due_mileage" value="{{ $vehicle->next_service_due_mileage }}" min="0" class="form-input-dark">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200/80 dark:border-white/[0.06]">
                        <a href="{{ route('vehicles.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                        <button type="submit" class="btn-primary">Update Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
