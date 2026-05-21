<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->vehicles()->with('serviceRecords');

        // Search filter
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Make filter
        if ($request->has('make') && $request->make) {
            $query->filterByMake($request->make);
        }

        // Year filter
        if ($request->has('year') && $request->year) {
            $query->filterByYear($request->year);
        }

        // Fuel type filter
        if ($request->has('fuel_type') && $request->fuel_type) {
            $query->filterByFuelType($request->fuel_type);
        }

        $vehicles = $query->latest()->paginate(10);

        // Get unique makes and years for filter dropdowns
        $makes = auth()->user()->vehicles()->distinct()->pluck('make')->sort()->values();
        $years = auth()->user()->vehicles()->distinct()->pluck('year')->sort()->values();
        $fuelTypes = auth()->user()->vehicles()->distinct()->pluck('fuel_type')->filter()->sort()->values();

        return view('vehicles.index', compact('vehicles', 'makes', 'years', 'fuelTypes'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'number_plate' => 'required|string|max:255|unique:vehicles,number_plate',
            'color' => 'nullable|string|max:255',
            'mileage' => 'nullable|numeric|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'vin_number' => 'nullable|string|max:17',
        ]);

        Vehicle::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'number_plate' => $request->number_plate,
            'color' => $request->color,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'vin_number' => $request->vin_number,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        $vehicle->load(['serviceRecords' => function($query) {
            $query->latest();
        }]);
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $request->validate([
            'name' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'number_plate' => 'required|string|max:255|unique:vehicles,number_plate,' . $vehicle->id,
            'color' => 'nullable|string|max:255',
            'mileage' => 'nullable|numeric|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'vin_number' => 'nullable|string|max:17',
        ]);

        $vehicle->update([
            'name' => $request->name,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'number_plate' => $request->number_plate,
            'color' => $request->color,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'vin_number' => $request->vin_number,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }

    protected function authorizeVehicle(Vehicle $vehicle)
    {
        if (auth()->user()->role !== 'admin' && $vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}
=======
use App\Models\Vehicle;
use App\Services\ActivityLogger;
use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    // Inject the dashboard analytics service used by the authenticated user's dashboard view.
    public function __construct(
        protected DashboardAnalyticsService $analytics
    ) {}

    // Display analytics data for the authenticated user's dashboard.
    public function dashboard()
    {
        $data = $this->analytics->forUser(Auth::user());

        return view('dashboard', $data);
    }

    // Show the vehicle listing page after authorization.
    public function index(Request $request)
    {
        $this->authorize('viewAny', Vehicle::class);

        return view('vehicles.index');
    }

    // Display the form to create a new vehicle.
    public function create()
    {
        $this->authorize('create', Vehicle::class);

        return view('vehicles.create');
    }

    // Validate and store a new vehicle, including optional image upload.
    public function store(Request $request)
    {
        $this->authorize('create', Vehicle::class);

        $request->merge([
            'vin_number' => $request->filled('vin_number') ? $request->vin_number : null,
        ]);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        unset($validated['image']);
        $vehicle = Vehicle::create($validated);

        ActivityLogger::log('vehicle.created', 'Vehicle "'.$vehicle->name.'" was created', $vehicle);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle added successfully!');
    }

    // Display a single vehicle detail page after authorization.
    public function show(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        return view('vehicles.show', compact('vehicle'));
    }

    // Display the edit form for a vehicle the user is authorized to update.
    public function edit(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        return view('vehicles.edit', compact('vehicle'));
    }

    // Validate and persist vehicle updates, including optional image replacement.
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $request->merge([
            'vin_number' => $request->filled('vin_number') ? $request->vin_number : null,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'number_plate' => 'required|string|max:255|unique:vehicles,number_plate,'.$vehicle->id,
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'color' => 'required|string|max:255',
            'mileage' => 'required|integer|min:0',
            'fuel_type' => 'required|string|max:255',
            'vin_number' => 'nullable|string|max:255|unique:vehicles,vin_number,'.$vehicle->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'next_service_due_date' => 'nullable|date',
            'next_service_due_mileage' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($vehicle->image_path) {
                Storage::disk('public')->delete($vehicle->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        unset($validated['image']);
        $vehicle->update($validated);

        ActivityLogger::log('vehicle.updated', 'Vehicle "'.$vehicle->name.'" was updated', $vehicle);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
    }

    // Soft delete the vehicle and remove its image from storage if present.
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        if ($vehicle->image_path) {
            Storage::disk('public')->delete($vehicle->image_path);
        }

        $vehicle->delete();
        ActivityLogger::log('vehicle.deleted', 'Vehicle "'.$vehicle->name.'" moved to trash', $vehicle);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle moved to trash.');
    }
}
>>>>>>> ec6237d (Third Week of Assignment small changes)
