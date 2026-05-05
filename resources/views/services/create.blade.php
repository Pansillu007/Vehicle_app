<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Add Service Record</h2>
                    <p class="text-gray-600 mb-4">Vehicle: {{ $vehicle->name }} ({{ $vehicle->number_plate }})</p>

                    <form method="POST" action="{{ route('vehicles.services.store', $vehicle) }}">
                        @csrf

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
                                    <option value="Oil Change" {{ old('service_type') == 'Oil Change' ? 'selected' : '' }}>Oil Change</option>
                                    <option value="Tire Rotation" {{ old('service_type') == 'Tire Rotation' ? 'selected' : '' }}>Tire Rotation</option>
                                    <option value="Brake Service" {{ old('service_type') == 'Brake Service' ? 'selected' : '' }}>Brake Service</option>
                                    <option value="Engine Repair" {{ old('service_type') == 'Engine Repair' ? 'selected' : '' }}>Engine Repair</option>
                                    <option value="Transmission" {{ old('service_type') == 'Transmission' ? 'selected' : '' }}>Transmission</option>
                                    <option value="Battery Replacement" {{ old('service_type') == 'Battery Replacement' ? 'selected' : '' }}>Battery Replacement</option>
                                    <option value="Air Filter" {{ old('service_type') == 'Air Filter' ? 'selected' : '' }}>Air Filter</option>
                                    <option value="Inspection" {{ old('service_type') == 'Inspection' ? 'selected' : '' }}>Inspection</option>
                                    <option value="Other" {{ old('service_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Date *</label>
                                <input type="date" name="service_date" value="{{ old('service_date') }}" 
                                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cost ($) *</label>
                                <input type="number" name="cost" value="{{ old('cost') }}" step="0.01" min="0" 
                                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Provider</label>
                                <input type="text" name="service_provider" value="{{ old('service_provider') }}" 
                                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" 
                                      class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md">
                                Save Service Record
                            </button>
                            <a href="{{ route('vehicles.services.index', $vehicle) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
