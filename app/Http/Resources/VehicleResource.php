<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'make' => $this->make,
            'model' => $this->model,
            'number_plate' => $this->number_plate,
            'year' => $this->year,
            'color' => $this->color,
            'mileage' => $this->mileage,
            'fuel_type' => $this->fuel_type,
            'vin_number' => $this->vin_number,
            'image_url' => $this->image_url,
            'next_service_due_date' => $this->next_service_due_date?->toDateString(),
            'next_service_due_mileage' => $this->next_service_due_mileage,
            'total_maintenance_cost' => $this->whenLoaded('serviceRecords', fn () => (float) $this->serviceRecords->sum('cost')),
            'service_records' => ServiceRecordResource::collection($this->whenLoaded('serviceRecords')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
