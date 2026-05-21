<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiVehicleController extends Controller
{
    // List vehicles accessible to the authenticated user or admin, with search and sorting support.
    public function index(Request $request)
    {
        $query = Auth::user()->isAdmin()
            ? Vehicle::query()
            : Auth::user()->vehicles();

        $vehicles = $query
            ->withCount('serviceRecords')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('number_plate', 'like', "%{$s}%"))
            ->when($request->sort === 'mileage', fn ($q) => $q->orderBy('mileage', $request->direction === 'asc' ? 'asc' : 'desc'))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return VehicleResource::collection($vehicles);
    }

    // Return a single vehicle resource after confirming view authorization.
    public function show(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);
        $vehicle->load('serviceRecords');

        return new VehicleResource($vehicle);
    }

    // Validate and create a new vehicle for the authenticated user.
    public function store(Request $request)
    {
        $this->authorize('create', Vehicle::class);

        $request->merge(['vin_number' => $request->filled('vin_number') ? $request->vin_number : null]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'number_plate' => 'required|string|max:255|unique:vehicles,number_plate',
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'color' => 'required|string|max:255',
            'mileage' => 'required|integer|min:0',
            'fuel_type' => 'required|string|max:255',
            'vin_number' => 'nullable|string|max:255|unique:vehicles,vin_number',
        ]);

        $validated['user_id'] = Auth::id();
        $vehicle = Vehicle::create($validated);

        return (new VehicleResource($vehicle))->response()->setStatusCode(201);
    }

    // Validate and update vehicle fields for the authorized vehicle.
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $request->merge(['vin_number' => $request->filled('vin_number') ? $request->vin_number : null]);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'make' => 'sometimes|string|max:255',
            'model' => 'sometimes|string|max:255',
            'number_plate' => 'sometimes|string|max:255|unique:vehicles,number_plate,'.$vehicle->id,
            'year' => 'sometimes|integer|min:1900|max:'.(date('Y') + 1),
            'color' => 'sometimes|string|max:255',
            'mileage' => 'sometimes|integer|min:0',
            'fuel_type' => 'sometimes|string|max:255',
            'vin_number' => 'nullable|string|max:255|unique:vehicles,vin_number,'.$vehicle->id,
        ]);

        $vehicle->update($validated);

        return new VehicleResource($vehicle->fresh());
    }

    // Soft delete the authorized vehicle and return a JSON confirmation.
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);
        $vehicle->delete();

        return response()->json(['message' => 'Vehicle moved to trash.'], 200);
    }
}
