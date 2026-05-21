<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    // Display trashed vehicles and service records available to the current user.
    public function index()
    {
        $user = Auth::user();

        $vehicleQuery = Vehicle::onlyTrashed()->with('user');
        $serviceQuery = ServiceRecord::onlyTrashed()->with('vehicle.user');

        if (! $user->isAdmin()) {
            $vehicleQuery->where('user_id', $user->id);
            $serviceQuery->whereHas('vehicle', fn ($q) => $q->where('user_id', $user->id));
        }

        $trashedVehicles = $vehicleQuery->latest('deleted_at')->paginate(10, ['*'], 'vehicles_page');
        $trashedServices = $serviceQuery->latest('deleted_at')->paginate(10, ['*'], 'services_page');

        return view('trash.index', compact('trashedVehicles', 'trashedServices'));
    }

    // Restore a soft-deleted vehicle after authorization checks.
    public function restoreVehicle(int $id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $vehicle);
        $vehicle->restore();
        ActivityLogger::log('vehicle.restored', 'Vehicle "'.$vehicle->name.'" restored', $vehicle);

        return back()->with('success', 'Vehicle restored.');
    }

    // Permanently delete a soft-deleted vehicle after authorization.
    public function forceDeleteVehicle(int $id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $vehicle);
        $vehicle->forceDelete();
        ActivityLogger::log('vehicle.force_deleted', 'Vehicle permanently deleted');

        return back()->with('success', 'Vehicle permanently deleted.');
    }

    // Restore a soft-deleted service record when the related vehicle is authorized.
    public function restoreService(int $id)
    {
        $service = ServiceRecord::onlyTrashed()->with('vehicle')->findOrFail($id);
        $this->authorize('update', $service->vehicle);
        $service->restore();
        ActivityLogger::log('service.restored', 'Service record restored', $service);

        return back()->with('success', 'Service record restored.');
    }

    // Permanently delete a soft-deleted service record after authorization.
    public function forceDeleteService(int $id)
    {
        $service = ServiceRecord::onlyTrashed()->with('vehicle')->findOrFail($id);
        $this->authorize('update', $service->vehicle);
        $service->forceDelete();
        ActivityLogger::log('service.force_deleted', 'Service permanently deleted');

        return back()->with('success', 'Service permanently deleted.');
    }
}
