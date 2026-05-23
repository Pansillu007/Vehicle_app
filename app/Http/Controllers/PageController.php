<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * View-only controllers — all CRUD mutations go through /api (Sanctum).
 */
class PageController extends Controller
{
    use AuthorizesRequests;

    public function dashboard()
    {
        return view('dashboard');
    }

    public function vehiclesIndex()
    {
        return view('vehicles.index');
    }

    public function vehiclesCreate()
    {
        return view('vehicles.create');
    }

    public function vehiclesShow(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        return view('vehicles.show', compact('vehicle'));
    }

    public function vehiclesEdit(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        return view('vehicles.edit', compact('vehicle'));
    }

    public function servicesCreate(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        return view('services.create', compact('vehicle'));
    }

    public function servicesEdit(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        return view('services.edit', compact('vehicle', 'service'));
    }
}
