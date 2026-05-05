<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRecord;
use App\Models\Vehicle;

class ServiceRecordApiController extends Controller
{
    public function index(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $query = $vehicle->serviceRecords();

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $serviceRecords = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $serviceRecords
        ]);
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

        $serviceRecord = $vehicle->serviceRecords()->create([
            'service_type' => $request->service_type,
            'description' => $request->description,
            'cost' => $request->cost,
            'service_date' => $request->service_date,
            'service_provider' => $request->service_provider,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service record created successfully',
            'data' => $serviceRecord
        ], 201);
    }

    public function show(Vehicle $vehicle, ServiceRecord $serviceRecord)
    {
        $this->authorizeServiceRecord($serviceRecord, $vehicle);

        return response()->json([
            'success' => true,
            'data' => $serviceRecord
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Service record updated successfully',
            'data' => $serviceRecord
        ]);
    }

    public function destroy(Vehicle $vehicle, ServiceRecord $serviceRecord)
    {
        $this->authorizeServiceRecord($serviceRecord, $vehicle);
        $serviceRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service record deleted successfully'
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

    protected function authorizeServiceRecord(ServiceRecord $serviceRecord, Vehicle $vehicle)
    {
        if ($serviceRecord->vehicle_id !== $vehicle->id) {
            abort(response()->json([
                'success' => false,
                'message' => 'Service record not found'
            ], 404));
        }
        $this->authorizeVehicle($vehicle);
    }
}
