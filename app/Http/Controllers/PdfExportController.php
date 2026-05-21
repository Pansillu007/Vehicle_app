<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PdfExportController extends Controller
{
    // Generate a vehicle report PDF for an authorized vehicle and stream it as a download.
    public function vehicleReport(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        $vehicle->load(['serviceRecords' => fn ($q) => $q->latest(), 'user']);

        $pdf = Pdf::loadView('pdf.vehicle-report', [
            'vehicle' => $vehicle,
            'totalCost' => $vehicle->serviceRecords->sum('cost'),
        ]);

        return $pdf->download('vehicle-'.$vehicle->number_plate.'-report.pdf');
    }

    // Generate a service history PDF for an authorized vehicle.
    public function serviceHistory(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        $services = $vehicle->serviceRecords()->latest()->get();

        $pdf = Pdf::loadView('pdf.service-history', compact('vehicle', 'services'));

        return $pdf->download('vehicle-'.$vehicle->number_plate.'-services.pdf');
    }

    // Generate a service invoice PDF for a specific service record and vehicle.
    public function serviceInvoice(Vehicle $vehicle, ServiceRecord $service)
    {
        $this->authorize('view', $vehicle);
        abort_unless($service->vehicle_id === $vehicle->id, 404);

        $pdf = Pdf::loadView('pdf.service-invoice', compact('vehicle', 'service'));

        return $pdf->download('invoice-'.$service->id.'.pdf');
    }
}
