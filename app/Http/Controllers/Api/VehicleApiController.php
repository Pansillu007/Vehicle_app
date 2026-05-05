<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleApiController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->vehicles()->with('serviceRecords');

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $vehicles = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
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
        ]);

        $vehicle = Vehicle::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'number_plate' => $request->number_plate,
            'color' => $request->color,
            'mileage' => $request->mileage,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully',
            'data' => $vehicle
        ], 201);
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        $vehicle->load('serviceRecords');

        return response()->json([
            'success' => true,
            'data' => $vehicle
        ]);
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
        ]);

        $vehicle->update([
            'name' => $request->name,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'number_plate' => $request->number_plate,
            'color' => $request->color,
            'mileage' => $request->mileage,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'data' => $vehicle
        ]);
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully'
        ]);
    }

    protected function authorizeVehicle(Vehicle $vehicle)
    {
        if (auth()->user()->role !== 'admin' && $vehicle->user_id !== auth()->id()) {
            abort(response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403));
        }
    }
}
