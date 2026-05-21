<x-app-layout>
<<<<<<< HEAD
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="text-gray-600 hover:text-gray-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 0l7 7m0 0l-7 7"></path>
                            </svg>
                        </a>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Edit Vehicle</h2>
                            <p class="text-gray-600 mt-1">Update your vehicle details</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6">
                    <form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
                        @csrf
                        @method('PUT')

                        <!-- Vehicle Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Vehicle Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $vehicle->name) }}" required
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="e.g., My Car, Family Van">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Make & Model -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="make" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Manufacturer <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="make" id="make" value="{{ old('make', $vehicle->make) }}" required
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('make') border-red-500 @enderror"
                                       placeholder="e.g., Toyota, Honda">
                                @error('make')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="model" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Model <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" required
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('model') border-red-500 @enderror"
                                       placeholder="e.g., Civic, Corolla">
                                @error('model')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Year & Number Plate -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Year
                                </label>
                                <input type="number" name="year" id="year" value="{{ old('year', $vehicle->year) }}" min="1900" max="{{ date('Y') + 1 }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-500 @enderror"
                                       placeholder="e.g., 2023">
                                @error('year')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="number_plate" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Registration Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="number_plate" id="number_plate" value="{{ old('number_plate', $vehicle->number_plate) }}" required
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('number_plate') border-red-500 @enderror"
                                       placeholder="e.g., ABC-1234">
                                @error('number_plate')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Color & Mileage -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="color" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Color
                                </label>
                                <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('color') border-red-500 @enderror"
                                       placeholder="e.g., Silver, Black">
                                @error('color')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="mileage" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Current Mileage (km)
                                </label>
                                <input type="number" name="mileage" id="mileage" value="{{ old('mileage', $vehicle->mileage) }}" step="0.01" min="0"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mileage') border-red-500 @enderror"
                                       placeholder="e.g., 15000">
                                @error('mileage')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Vehicle
                            </button>
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

=======
    <x-slot name="header">
        <h2 class="page-header-title">{{ __('Edit Vehicle') }}: {{ $vehicle->name }}</h2>
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

                <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center gap-4 mb-2">
                        <img src="{{ $vehicle->image_url }}" alt="" class="w-24 h-24 rounded-2xl object-cover">
                        <div>
                            <label for="image" class="form-label">Update image</label>
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-input-dark">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label">Vehicle Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $vehicle->name) }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="number_plate" class="form-label">Number Plate</label>
                            <input type="text" name="number_plate" id="number_plate" value="{{ old('number_plate', $vehicle->number_plate) }}" required class="form-input-dark uppercase">
                        </div>
                        <div>
                            <label for="make" class="form-label">Make</label>
                            <input type="text" name="make" id="make" value="{{ old('make', $vehicle->make) }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="model" class="form-label">Model</label>
                            <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="year" class="form-label">Year</label>
                            <input type="number" name="year" id="year" value="{{ old('year', $vehicle->year) }}" required min="1900" max="{{ date('Y') + 1 }}" class="form-input-dark">
                        </div>
                        <div>
                            <label for="color" class="form-label">Color</label>
                            <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}" required class="form-input-dark">
                        </div>
                        <div>
                            <label for="mileage" class="form-label">Current Mileage</label>
                            <input type="number" name="mileage" id="mileage" value="{{ old('mileage', $vehicle->mileage) }}" required min="0" step="1" class="form-input-dark">
                        </div>
                        <div>
                            <label for="fuel_type" class="form-label">Fuel Type</label>
                            <select name="fuel_type" id="fuel_type" required class="form-input-dark">
                                @foreach(['Petrol','Diesel','Electric','Hybrid','Other'] as $fuel)
                                    <option value="{{ $fuel }}" {{ old('fuel_type', $vehicle->fuel_type) == $fuel ? 'selected' : '' }}>{{ $fuel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="vin_number" class="form-label">VIN Number (Optional)</label>
                            <input type="text" name="vin_number" id="vin_number" value="{{ old('vin_number', $vehicle->vin_number) }}" class="form-input-dark uppercase">
                        </div>
                        <div>
                            <label for="next_service_due_date" class="form-label">Next service due date</label>
                            <input type="date" name="next_service_due_date" id="next_service_due_date" value="{{ old('next_service_due_date', $vehicle->next_service_due_date?->format('Y-m-d')) }}" class="form-input-dark">
                        </div>
                        <div>
                            <label for="next_service_due_mileage" class="form-label">Next service due mileage</label>
                            <input type="number" name="next_service_due_mileage" id="next_service_due_mileage" value="{{ old('next_service_due_mileage', $vehicle->next_service_due_mileage) }}" min="0" class="form-input-dark">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200/80 dark:border-white/[0.06]">
                        <a href="{{ route('vehicles.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                        <button type="submit" class="btn-primary">Update Vehicle</button>
                    </div>
                </form>
            </div>
>>>>>>> ec6237d (Third Week of Assignment small changes)
        </div>
    </div>
</x-app-layout>
