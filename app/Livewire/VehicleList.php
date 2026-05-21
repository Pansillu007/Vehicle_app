<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $fuelFilter = '';

    public string $sort = 'latest';

    protected $queryString = ['search', 'fuelFilter', 'sort'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Auth::user()->isAdmin()
            ? Vehicle::query()->with('user')
            : Auth::user()->vehicles();

        $vehicles = $query
            ->when($this->search, function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('number_plate', 'like', $term)
                        ->orWhere('make', 'like', $term)
                        ->orWhere('model', 'like', $term);
                });
            })
            ->when($this->fuelFilter, fn ($q) => $q->where('fuel_type', $this->fuelFilter))
            ->when($this->sort === 'mileage', fn ($q) => $q->orderByDesc('mileage'))
            ->when($this->sort === 'name', fn ($q) => $q->orderBy('name'))
            ->when($this->sort === 'latest', fn ($q) => $q->latest())
            ->paginate(9);

        return view('livewire.vehicle-list', compact('vehicles'));
    }
}
