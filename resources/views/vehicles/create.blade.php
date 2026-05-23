<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">{{ __('Add New Vehicle') }}</h2>
    </x-slot>

    <div class="page-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-6 sm:p-8">
                <div id="api-validation-errors" class="hidden alert-error mb-6"></div>

                <form id="vehicle-api-form" class="space-y-6" data-redirect-url="{{ route('vehicles.index') }}">
                    <div>
                        <label for="image" class="form-label">Vehicle Image</label>
                        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-input-dark file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
                        <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG, or WebP.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label">Vehicle Name</label>
                            <input type="text" name="name" id="name" required class="form-input-dark" placeholder="My Daily Driver">
                        </div>
                        <div>
                            <label for="number_plate" class="form-label">Number Plate</label>
                            <input type="text" name="number_plate" id="number_plate" required class="form-input-dark uppercase" placeholder="ABC-1234">
                        </div>
                        <div>
                            <label for="make" class="form-label">Make</label>
                            <input type="text" name="make" id="make" required class="form-input-dark" placeholder="Toyota">
                        </div>
                        <div>
                            <label for="model" class="form-label">Model</label>
                            <input type="text" name="model" id="model" required class="form-input-dark" placeholder="Corolla">
                        </div>
                        <div>
                            <label for="year" class="form-label">Year</label>
                            <input type="number" name="year" id="year" required min="1900" max="{{ date('Y') + 1 }}" class="form-input-dark">
                        </div>
                        <div>
                            <label for="color" class="form-label">Color</label>
                            <input type="text" name="color" id="color" required class="form-input-dark" placeholder="Silver">
                        </div>
                        <div>
                            <label for="mileage" class="form-label">Current Mileage</label>
                            <input type="number" name="mileage" id="mileage" required min="0" step="1" class="form-input-dark">
                        </div>
                        <div>
                            <label for="fuel_type" class="form-label">Fuel Type</label>
                            <select name="fuel_type" id="fuel_type" required class="form-input-dark">
                                <option value="" disabled selected>Select Fuel Type</option>
                                @foreach(['Petrol','Diesel','Electric','Hybrid','Other'] as $fuel)
                                    <option value="{{ $fuel }}">{{ $fuel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="vin_number" class="form-label">VIN Number (Optional)</label>
                            <input type="text" name="vin_number" id="vin_number" class="form-input-dark uppercase" placeholder="1HGBH41JXMN109186">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200/80 dark:border-white/[0.06]">
                        <a href="{{ route('vehicles.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                        <button type="submit" class="btn-primary">Save Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
