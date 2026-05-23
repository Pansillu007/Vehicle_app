<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\RespondsWithApiJson;
use App\Http\Controllers\Controller;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ApiTrashController extends Controller
{
    use RespondsWithApiJson;

    public function index(Request $request)
    {
        $user = $request->user();

        $vehicleQuery = Vehicle::onlyTrashed()->with('user');
        $serviceQuery = ServiceRecord::onlyTrashed()->with('vehicle.user');

        if (! $user->isAdmin()) {
            $vehicleQuery->where('user_id', $user->id);
            $serviceQuery->whereHas('vehicle', fn ($q) => $q->where('user_id', $user->id));
        }

        $vehicles = $vehicleQuery->latest('deleted_at')->paginate(10, ['*'], 'vehicles_page');
        $services = $serviceQuery->latest('deleted_at')->paginate(10, ['*'], 'services_page');

        return $this->apiSuccess([
            'vehicles' => [
                'items' => $vehicles->map(fn (Vehicle $v) => [
                    'id' => $v->id,
                    'name' => $v->name,
                    'owner_name' => $v->user?->name,
                    'deleted_at_human' => $v->deleted_at?->diffForHumans(),
                ]),
                'meta' => [
                    'current_page' => $vehicles->currentPage(),
                    'last_page' => $vehicles->lastPage(),
                ],
            ],
            'services' => [
                'items' => $services->map(fn (ServiceRecord $s) => [
                    'id' => $s->id,
                    'service_type' => $s->service_type,
                    'vehicle_name' => $s->vehicle?->name,
                    'deleted_at_human' => $s->deleted_at?->diffForHumans(),
                ]),
                'meta' => [
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                ],
            ],
        ], 'Trash loaded.');
    }

    public function restoreVehicle(int $id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $vehicle);
        $vehicle->restore();
        ActivityLogger::log('vehicle.restored', 'Vehicle "'.$vehicle->name.'" restored', $vehicle);

        return $this->apiSuccess(null, 'Vehicle restored.');
    }

    public function forceDeleteVehicle(int $id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $vehicle);
        $vehicle->forceDelete();
        ActivityLogger::log('vehicle.force_deleted', 'Vehicle permanently deleted');

        return $this->apiSuccess(null, 'Vehicle permanently deleted.');
    }

    public function restoreService(int $id)
    {
        $service = ServiceRecord::onlyTrashed()->with('vehicle')->findOrFail($id);
        $this->authorize('update', $service->vehicle);
        $service->restore();
        ActivityLogger::log('service.restored', 'Service record restored', $service);

        return $this->apiSuccess(null, 'Service record restored.');
    }

    public function forceDeleteService(int $id)
    {
        $service = ServiceRecord::onlyTrashed()->with('vehicle')->findOrFail($id);
        $this->authorize('update', $service->vehicle);
        $service->forceDelete();
        ActivityLogger::log('service.force_deleted', 'Service permanently deleted');

        return $this->apiSuccess(null, 'Service permanently deleted.');
    }
}
