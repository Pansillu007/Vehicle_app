<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRecordResource;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use App\Services\ServiceReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiServiceController extends Controller
{
    // Inject the service reminder helper used to keep vehicle schedules updated after changes.
    public function __construct(
        protected ServiceReminderService $reminders
    ) {}

    // Return paginated service records for a given vehicle after view authorization.
    public function index(Request $request, Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        $services = $vehicle->serviceRecords()
            ->when($request->type, fn ($q, $type) => $q->where('service_type', 'like', "%{$type}%"))
            ->latest('service_date')
            ->paginate($request->integer('per_page', 15));

        return ServiceRecordResource::collection($services);
    }

    // Validate and create a new service record for the authorized vehicle.
    public function store(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'description' => 'required|string',
            'service_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'service_provider' => 'required|string|max:255',
        ]);

        $record = $vehicle->serviceRecords()->create($validated);
        $this->reminders->updateVehicleSchedule($vehicle->fresh(), $record);

        return (new ServiceRecordResource($record))->response()->setStatusCode(201);
    }

    // Return a single service record resource, ensuring the record belongs to the vehicle.
    public function show(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('view', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        return new ServiceRecordResource($service);
    }

    // Validate and update an existing service record for the authorized vehicle.
    public function update(Request $request, Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        $validated = $request->validate([
            'service_type' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'service_date' => 'sometimes|date',
            'cost' => 'sometimes|numeric|min:0',
            'mileage' => 'sometimes|integer|min:0',
            'service_provider' => 'sometimes|string|max:255',
        ]);

        $service->update($validated);
        $this->reminders->updateVehicleSchedule($vehicle->fresh(), $service->fresh());

        return new ServiceRecordResource($service);
    }

    // Soft delete the requested service record and return a JSON confirmation message.
    public function destroy(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);
        $service->delete();

        return response()->json(['message' => 'Service record moved to trash.']);
    }
}
