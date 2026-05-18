<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->vehicles()->with('serviceRecords');

        // Search filter
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Make filter
        if ($request->has('make') && $request->make) {
            $query->filterByMake($request->make);
        }

        // Year filter
        if ($request->has('year') && $request->year) {
            $query->filterByYear($request->year);
        }

        // Fuel type filter
        if ($request->has('fuel_type') && $request->fuel_type) {
            $query->filterByFuelType($request->fuel_type);
        }

        $vehicles = $query->latest()->paginate(10);

        // Get unique makes and years for filter dropdowns
        $makes = auth()->user()->vehicles()->distinct()->pluck('make')->sort()->values();
        $years = auth()->user()->vehicles()->distinct()->pluck('year')->sort()->values();
        $fuelTypes = auth()->user()->vehicles()->distinct()->pluck('fuel_type')->filter()->sort()->values();

        return view('vehicles.index', compact('vehicles', 'makes', 'years', 'fuelTypes'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'number_plate' => 'required|string|max:255|unique:vehicles,number_plate',
            'color' => 'nullable|string|max:255',
            'mileage' => 'nullable|numeric|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'vin_number' => 'nullable|string|max:17',
        ]);

        Vehicle::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'number_plate' => $request->number_plate,
            'color' => $request->color,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'vin_number' => $request->vin_number,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        $vehicle->load(['serviceRecords' => function($query) {
            $query->latest();
        }]);
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $request->validate([
            'name' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'number_plate' => 'required|string|max:255|unique:vehicles,number_plate,' . $vehicle->id,
            'color' => 'nullable|string|max:255',
            'mileage' => 'nullable|numeric|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'vin_number' => 'nullable|string|max:17',
        ]);

        $vehicle->update([
            'name' => $request->name,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'number_plate' => $request->number_plate,
            'color' => $request->color,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'vin_number' => $request->vin_number,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }

    protected function authorizeVehicle(Vehicle $vehicle)
    {
        if (auth()->user()->role !== 'admin' && $vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}