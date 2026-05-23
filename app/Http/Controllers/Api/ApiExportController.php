<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Services\CsvExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiExportController extends Controller
{
    public function __construct(
        protected CsvExportService $csv
    ) {}

    public function vehicles(Request $request)
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

        $vehicles = $query->withCount('serviceRecords')->get();
        $csv = $this->csv->vehicles($vehicles, Auth::user()->isAdmin());
        $filename = 'vehicles-'.now()->format('Y-m-d').'.csv';

        return $this->csvResponse($csv, $filename);
    }

    public function vehicleServices(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        $services = $vehicle->serviceRecords()->latest('service_date')->get();
        $csv = $this->csv->services($services, $vehicle);
        $filename = 'vehicle-'.$vehicle->number_plate.'-services-'.now()->format('Y-m-d').'.csv';

        return $this->csvResponse($csv, $filename);
    }

    protected function csvResponse(string $csv, string $filename)
    {
        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
