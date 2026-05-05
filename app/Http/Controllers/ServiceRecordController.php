<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRecord;
use App\Models\Vehicle;

class ServiceRecordController extends Controller
{
    public function index(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $query = $vehicle->serviceRecords();

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('date')) {
            $query->filterByDate($request->date);
        }

        $serviceRecords = $query->latest()->paginate(10);

        return view('services.index', compact('vehicle', 'serviceRecords'));
    }

    public function create(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        return view('services.create', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $request->validate([
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'service_date' => 'required|date',
            'service_provider' => 'nullable|string|max:255',
        ]);

        $vehicle->serviceRecords()->create([
            'service_type' => $request->service_type,
            'description' => $request->description,
            'cost' => $request->cost,
            'service_date' => $request->service_date,
            'service_provider' => $request->service_provider,
        ]);

        return redirect()->route('vehicles.services.index', $vehicle)
            ->with('success', 'Service record created successfully.');
    }

    public function edit(Vehicle $vehicle, ServiceRecord $serviceRecord)
    {
        $this->authorizeServiceRecord($serviceRecord, $vehicle);
        return view('services.edit', compact('vehicle', 'serviceRecord'));
    }

    public function update(Request $request, Vehicle $vehicle, ServiceRecord $serviceRecord)
    {
        $this->authorizeServiceRecord($serviceRecord, $vehicle);

        $request->validate([
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'service_date' => 'required|date',
            'service_provider' => 'nullable|string|max:255',
        ]);

        $serviceRecord->update([
            'service_type' => $request->service_type,
            'description' => $request->description,
            'cost' => $request->cost,
            'service_date' => $request->service_date,
            'service_provider' => $request->service_provider,
        ]);

        return redirect()->route('vehicles.services.index', $vehicle)
            ->with('success', 'Service record updated successfully.');
    }

    public function destroy(Vehicle $vehicle, ServiceRecord $serviceRecord)
    {
        $this->authorizeServiceRecord($serviceRecord, $vehicle);
        $serviceRecord->delete();

        return redirect()->route('vehicles.services.index', $vehicle)
            ->with('success', 'Service record deleted successfully.');
    }

    protected function authorizeVehicle(Vehicle $vehicle)
    {
        if (auth()->user()->role !== 'admin' && $vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
    }

    protected function authorizeServiceRecord(ServiceRecord $serviceRecord, Vehicle $vehicle)
    {
        if ($serviceRecord->vehicle_id !== $vehicle->id) {
            abort(404, 'Service record not found.');
        }
        $this->authorizeVehicle($vehicle);
    }
}
