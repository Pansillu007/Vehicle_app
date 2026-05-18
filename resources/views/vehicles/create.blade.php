<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('vehicles.index') }}" class="text-gray-600 hover:text-gray-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 0l7 7m0 0l-7 7"></path>
                            </svg>
                        </a>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Add New Vehicle</h2>
                            <p class="text-gray-600 mt-1">Fill in your vehicle details</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6">
                    <form method="POST" action="{{ route('vehicles.store') }}">
                        @csrf

                        <!-- Vehicle Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Vehicle Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                                <input type="text" name="make" id="make" value="{{ old('make') }}" required
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
                                <input type="text" name="model" id="model" value="{{ old('model') }}" required
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
                                <input type="number" name="year" id="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}"
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
                                <input type="text" name="number_plate" id="number_plate" value="{{ old('number_plate') }}" required
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
                                <input type="text" name="color" id="color" value="{{ old('color') }}"
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
                                <input type="number" name="mileage" id="mileage" value="{{ old('mileage') }}" step="0.01" min="0"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mileage') border-red-500 @enderror"
                                       placeholder="e.g., 15000">
                                @error('mileage')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Fuel Type & VIN Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="fuel_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Fuel Type
                                </label>
                                <select name="fuel_type" id="fuel_type"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fuel_type') border-red-500 @enderror">
                                    <option value="">Select Fuel Type</option>
                                    <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                    <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="Electric" {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                                    <option value="Hybrid" {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    <option value="LPG" {{ old('fuel_type') == 'LPG' ? 'selected' : '' }}>LPG</option>
                                </select>
                                @error('fuel_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="vin_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    VIN Number (Chassis Number)
                                </label>
                                <input type="text" name="vin_number" id="vin_number" value="{{ old('vin_number') }}" maxlength="17"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vin_number') border-red-500 @enderror"
                                       placeholder="17-character VIN">
                                @error('vin_number')
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
                                Create Vehicle
                            </button>
                            <a href="{{ route('vehicles.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
