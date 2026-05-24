<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\RespondsWithApiJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRecordResource;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use App\Services\ActivityLogger;
use App\Services\ServiceReminderService;
use Illuminate\Http\Request;

class ApiServiceController extends Controller
{
    use RespondsWithApiJson;

    public function __construct(
        protected ServiceReminderService $reminders
    ) {}

    public function index(Request $request, Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        $services = $vehicle->serviceRecords()
            ->when($request->search, function ($q, $search) {
                $term = '%'.$search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('service_type', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('service_provider', 'like', $term);
                });
            })
            ->when($request->type, fn ($q, $type) => $q->where('service_type', 'like', "%{$type}%"))
            ->latest('service_date')
            ->paginate($request->integer('per_page', 50));

        return $this->apiPaginated($services, ServiceRecordResource::class);
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $this->validateService($request);
        $record = $vehicle->serviceRecords()->create($validated);
        $this->reminders->updateVehicleSchedule($vehicle->fresh(), $record);

        ActivityLogger::log('service.created', 'Service "'.$record->service_type.'" added to '.$vehicle->name, $record);

        return $this->apiResource(
            new ServiceRecordResource($record),
            'Service record created successfully.',
            201
        );
    }

    public function show(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('view', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        return $this->apiResource(new ServiceRecordResource($service));
    }

    public function update(Request $request, Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        $validated = $this->validateService($request, true);
        $service->update($validated);
        $this->reminders->updateVehicleSchedule($vehicle->fresh(), $service->fresh());

        ActivityLogger::log('service.updated', 'Service "'.$service->service_type.'" updated', $service);

        return $this->apiResource(
            new ServiceRecordResource($service),
            'Service record updated successfully.'
        );
    }

    public function destroy(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        $service->delete();
        ActivityLogger::log('service.deleted', 'Service record moved to trash', $service);

        return $this->apiSuccess(null, 'Service record moved to trash.');
    }

    protected function validateService(Request $request, bool $partial = false): array
    {
        $prefix = $partial ? 'sometimes|' : '';

        return $request->validate([
            'service_type' => $prefix.'required|string|max:255',
            'description' => $prefix.'required|string',
            'service_date' => $prefix.'required|date',
            'cost' => $prefix.'required|numeric|min:0',
            'mileage' => $prefix.'required|integer|min:0',
            'service_provider' => $prefix.'required|string|max:255',
        ]);
    }
}
