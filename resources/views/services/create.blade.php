<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">{{ __('Add Service Record') }} — {{ $vehicle->name }}</h2>
    </x-slot>

    <div class="page-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-6 sm:p-8">
                <form id="service-api-form" class="space-y-6"
                    data-vehicle-id="{{ $vehicle->id }}"
                    data-redirect-url="{{ route('vehicles.show', $vehicle) }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="service_type" class="form-label">Service Type</label>
                            <input type="text" name="service_type" id="service_type" required class="form-input-dark" placeholder="Oil Change">
                        </div>
                        <div>
                            <label for="service_provider" class="form-label">Service Provider</label>
                            <input type="text" name="service_provider" id="service_provider" required class="form-input-dark" placeholder="AutoCare Garage">
                        </div>
                        <div>
                            <label for="service_date" class="form-label">Service Date</label>
                            <input type="date" name="service_date" id="service_date" value="{{ date('Y-m-d') }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="cost" class="form-label">Cost ($)</label>
                            <input type="number" name="cost" id="cost" required min="0" step="0.01" class="form-input-dark">
                        </div>
                        <div class="md:col-span-2">
                            <label for="mileage" class="form-label">Mileage at Service</label>
                            <input type="number" name="mileage" id="mileage" value="{{ $vehicle->mileage }}" required min="0" step="1" class="form-input-dark md:max-w-xs">
                        </div>
                        <div class="md:col-span-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="4" required class="form-input-dark"></textarea>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200/80 dark:border-white/[0.06]">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                        <button type="submit" class="btn-primary">Save Service Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
