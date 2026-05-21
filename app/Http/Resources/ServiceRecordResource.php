<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'service_date' => $this->service_date?->toDateString(),
            'cost' => (float) $this->cost,
            'mileage' => $this->mileage,
            'service_provider' => $this->service_provider,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
