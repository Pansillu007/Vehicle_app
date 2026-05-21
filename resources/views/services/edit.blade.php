<x-app-layout>
<<<<<<< HEAD
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Service Record</h2>
                    <p class="text-gray-600 mb-4">Vehicle: {{ $vehicle->name }} ({{ $vehicle->number_plate }})</p>

                    <form method="POST" action="{{ route('vehicles.services.update', [$vehicle, $serviceRecord]) }}">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                                <select name="service_type" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Select Type</option>
                                    @php
                                        $types = ['Oil Change', 'Tire Rotation', 'Brake Service', 'Engine Repair', 'Transmission', 'Battery Replacement', 'Air Filter', 'Inspection', 'Other'];
                                    @endphp
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ old('service_type', $serviceRecord->service_type) == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Date *</label>
                                <input type="date" name="service_date" value="{{ old('service_date', $serviceRecord->service_date->format('Y-m-d')) }}" 
                                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cost ($) *</label>
                                <input type="number" name="cost" value="{{ old('cost', $serviceRecord->cost) }}" step="0.01" min="0" 
                                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Provider</label>
                                <input type="text" name="service_provider" value="{{ old('service_provider', $serviceRecord->service_provider) }}" 
                                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" 
                                      class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $serviceRecord->description) }}</textarea>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md">
                                Update Service Record
                            </button>
                            <a href="{{ route('vehicles.services.index', $vehicle) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
=======
    <x-slot name="header">
        <h2 class="page-header-title">{{ __('Edit Service Record') }} — {{ $vehicle->name }}</h2>
    </x-slot>

    <div class="page-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-6 sm:p-8">
                @if ($errors->any())
                    <div class="alert-error mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('vehicles.services.update', [$vehicle, $service]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="service_type" class="form-label">Service Type</label>
                            <input type="text" name="service_type" id="service_type" value="{{ old('service_type', $service->service_type) }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="service_provider" class="form-label">Service Provider</label>
                            <input type="text" name="service_provider" id="service_provider" value="{{ old('service_provider', $service->service_provider) }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="service_date" class="form-label">Service Date</label>
                            <input type="date" name="service_date" id="service_date" value="{{ old('service_date', $service->service_date->format('Y-m-d')) }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="cost" class="form-label">Cost ($)</label>
                            <input type="number" name="cost" id="cost" value="{{ old('cost', $service->cost) }}" required min="0" step="0.01" class="form-input-dark">
                        </div>
                        <div class="md:col-span-2">
                            <label for="mileage" class="form-label">Mileage at Service</label>
                            <input type="number" name="mileage" id="mileage" value="{{ old('mileage', $service->mileage) }}" required min="0" step="1" class="form-input-dark md:max-w-xs">
                        </div>
                        <div class="md:col-span-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="4" required class="form-input-dark">{{ old('description', $service->description) }}</textarea>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200/80 dark:border-white/[0.06]">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                        <button type="submit" class="btn-primary">Update Service Record</button>
                    </div>
                </form>
>>>>>>> ec6237d (Third Week of Assignment small changes)
            </div>
        </div>
    </div>
</x-app-layout>
