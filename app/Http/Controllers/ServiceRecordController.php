<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
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
=======
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use App\Notifications\ServiceDueNotification;
use App\Services\ActivityLogger;
use App\Services\ServiceReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRecordController extends Controller
{
    // Inject the service reminder helper used to keep vehicle schedules in sync with service records.
    public function __construct(
        protected ServiceReminderService $reminders
    ) {}

    // Redirect the service index request to the parent vehicle show page.
    public function index(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        return redirect()->route('vehicles.show', $vehicle);
    }

    // Display the form for creating a new service record on the selected vehicle.
    public function create(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        return view('services.create', compact('vehicle'));
    }

    // Validate and store a new service record, then update reminder scheduling and notify the user.
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

        ActivityLogger::log('service.created', 'Service "'.$record->service_type.'" added to '.$vehicle->name, $record);

        Auth::user()->notify(new ServiceDueNotification($vehicle->fresh()));

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Service record added successfully!');
    }

    // Handle direct service record show requests by redirecting back to the vehicle view.
    public function show(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('view', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        return redirect()->route('vehicles.show', $vehicle);
    }

    // Display the edit form for the requested service record if the vehicle is authorized.
    public function edit(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        return view('services.edit', compact('vehicle', 'service'));
    }

    // Validate and persist service record updates, then refresh reminder scheduling for the vehicle.
    public function update(Request $request, Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'description' => 'required|string',
            'service_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'service_provider' => 'required|string|max:255',
        ]);

        $service->update($validated);
        $this->reminders->updateVehicleSchedule($vehicle->fresh(), $service->fresh());

        ActivityLogger::log('service.updated', 'Service "'.$service->service_type.'" updated', $service);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Service record updated successfully!');
    }

    // Soft delete the requested service record and log the action.
    public function destroy(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('update', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        $service->delete();
        ActivityLogger::log('service.deleted', 'Service record moved to trash', $service);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Service record moved to trash.');
>>>>>>> ec6237d (Third Week of Assignment small changes)
    }
}
