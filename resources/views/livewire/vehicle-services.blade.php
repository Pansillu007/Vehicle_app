<div>
    <div class="glass-card rounded-3xl p-4 sm:p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="form-label">Filter services</label>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-input-dark" placeholder="Type, provider, notes...">
            </div>
            <div>
                <label class="form-label">Service type</label>
                <input type="text" wire:model.live.debounce.300ms="typeFilter" class="form-input-dark" placeholder="e.g. Oil Change">
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">Total filtered cost: <span class="font-bold text-blue-500">${{ number_format($totalCost, 2) }}</span></p>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type / Provider</th>
                        <th>Cost</th>
                        <th>Mileage</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr wire:key="service-{{ $service->id }}">
                        <td class="whitespace-nowrap font-medium text-gray-900 dark:text-white">{{ $service->service_date->format('M d, Y') }}</td>
                        <td>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $service->service_type }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $service->service_provider }}</div>
                        </td>
                        <td class="font-semibold">${{ number_format($service->cost, 2) }}</td>
                        <td>{{ number_format($service->mileage) }}</td>
                        <td class="text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('vehicles.services.invoice', [$vehicle, $service]) }}" class="text-xs text-cyan-600 dark:text-cyan-400 hover:underline">PDF</a>
                            <a href="{{ route('vehicles.services.edit', [$vehicle, $service]) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                            <form action="{{ route('vehicles.services.destroy', [$vehicle, $service]) }}" method="POST" class="inline" onsubmit="return confirm('Move to trash?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline bg-transparent border-0 cursor-pointer">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-12 text-center text-gray-500">No service records match your filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
