<?php

namespace App\Services;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Support\Collection;

class CsvExportService
{
    public function vehicles(Collection $vehicles, bool $includeOwner = false): string
    {
        $headers = array_filter([
            'ID',
            'Name',
            'Make',
            'Model',
            'Number Plate',
            'Year',
            'Color',
            'Mileage',
            'Fuel Type',
            'VIN',
            $includeOwner ? 'Owner' : null,
            'Service Records',
            'Next Service Date',
            'Next Service Mileage',
        ]);

        return $this->build($headers, $vehicles->map(function (Vehicle $vehicle) use ($includeOwner) {
            $row = [
                $vehicle->id,
                $vehicle->name,
                $vehicle->make,
                $vehicle->model,
                $vehicle->number_plate,
                $vehicle->year,
                $vehicle->color,
                $vehicle->mileage,
                $vehicle->fuel_type,
                $vehicle->vin_number ?? '',
            ];

            if ($includeOwner) {
                $row[] = $vehicle->user?->name ?? '';
            }

            $row[] = $vehicle->service_records_count ?? $vehicle->serviceRecords()->count();
            $row[] = $vehicle->next_service_due_date?->toDateString() ?? '';
            $row[] = $vehicle->next_service_due_mileage ?? '';

            return $row;
        }));
    }

    public function services(Collection $services, Vehicle $vehicle): string
    {
        $headers = [
            'Vehicle',
            'Number Plate',
            'Service ID',
            'Service Type',
            'Service Date',
            'Cost',
            'Mileage',
            'Provider',
            'Description',
        ];

        return $this->build($headers, $services->map(function (ServiceRecord $service) use ($vehicle) {
            return [
                $vehicle->name,
                $vehicle->number_plate,
                $service->id,
                $service->service_type,
                $service->service_date?->toDateString(),
                $service->cost,
                $service->mileage,
                $service->service_provider,
                $service->description,
            ];
        }));
    }

    protected function build(array $headers, Collection $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        fclose($handle);

        return $contents ?: '';
    }
}
