<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class VehicleServices extends Component
{
    use AuthorizesRequests;

    public Vehicle $vehicle;

    public string $search = '';

    public string $typeFilter = '';

    public function mount(Vehicle $vehicle): void
    {
        $this->authorize('view', $vehicle);
        $this->vehicle = $vehicle;
    }

    public function render()
    {
        $services = $this->vehicle->serviceRecords()
            ->when($this->search, function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('service_type', 'like', $term)
                        ->orWhere('service_provider', 'like', $term)
                        ->orWhere('description', 'like', $term);
                });
            })
            ->when($this->typeFilter, fn ($q) => $q->where('service_type', 'like', '%'.$this->typeFilter.'%'))
            ->latest('service_date')
            ->get();

        $totalCost = $services->sum('cost');

        return view('livewire.vehicle-services', compact('services', 'totalCost'));
    }
}
