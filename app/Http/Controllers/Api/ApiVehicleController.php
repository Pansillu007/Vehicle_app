<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\RespondsWithApiJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiVehicleController extends Controller
{
    use RespondsWithApiJson;

    public function index(Request $request)
    {
        $query = Auth::user()->isAdmin()
            ? Vehicle::query()->with('user')
            : Auth::user()->vehicles();

        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('make', 'like', $term)
                    ->orWhere('model', 'like', $term)
                    ->orWhere('number_plate', 'like', $term);
            });
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        $sort = $request->get('sort', 'latest');
        $query = match ($sort) {
            'mileage' => $query->orderByDesc('mileage'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $vehicles = $query->withCount('serviceRecords')->paginate($request->integer('per_page', 12));

        return $this->apiPaginated($vehicles, VehicleResource::class);
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);
        $vehicle->load(['serviceRecords' => fn ($q) => $q->latest(), 'user']);

        return $this->apiResource(new VehicleResource($vehicle));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Vehicle::class);

        $validated = $this->validateVehicle($request);
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create($validated);
        ActivityLogger::log('vehicle.created', 'Vehicle "'.$vehicle->name.'" was added', $vehicle);

        return $this->apiResource(
            new VehicleResource($vehicle),
            'Vehicle created successfully.',
            201
        );
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $this->validateVehicle($request, $vehicle);

        if ($request->hasFile('image')) {
            if ($vehicle->image_path) {
                Storage::disk('public')->delete($vehicle->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($validated);
        ActivityLogger::log('vehicle.updated', 'Vehicle "'.$vehicle->name.'" was updated', $vehicle);

        return $this->apiResource(
            new VehicleResource($vehicle->fresh()),
            'Vehicle updated successfully.'
        );
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);
        $vehicle->delete();
        ActivityLogger::log('vehicle.deleted', 'Vehicle "'.$vehicle->name.'" moved to trash', $vehicle);

        return $this->apiSuccess(null, 'Vehicle moved to trash.');
    }

    protected function validateVehicle(Request $request, ?Vehicle $vehicle = null): array
    {
        $request->merge([
            'vin_number' => $request->filled('vin_number') ? $request->vin_number : null,
        ]);

        return $request->validate([
            'name' => ($vehicle ? 'sometimes|' : '').'required|string|max:255',
            'make' => ($vehicle ? 'sometimes|' : '').'required|string|max:255',
            'model' => ($vehicle ? 'sometimes|' : '').'required|string|max:255',
            'number_plate' => ($vehicle ? 'sometimes|' : '').'required|string|max:255|unique:vehicles,number_plate,'.($vehicle?->id ?? 'NULL'),
            'year' => ($vehicle ? 'sometimes|' : '').'required|integer|min:1900|max:'.(date('Y') + 1),
            'color' => ($vehicle ? 'sometimes|' : '').'required|string|max:255',
            'mileage' => ($vehicle ? 'sometimes|' : '').'required|integer|min:0',
            'fuel_type' => ($vehicle ? 'sometimes|' : '').'required|string|max:255',
            'vin_number' => 'nullable|string|max:255|unique:vehicles,vin_number,'.($vehicle?->id ?? 'NULL'),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'next_service_due_date' => 'nullable|date',
            'next_service_due_mileage' => 'nullable|integer|min:0',
        ], [], [
            'number_plate' => 'number plate',
            'fuel_type' => 'fuel type',
        ]);
    }
}
