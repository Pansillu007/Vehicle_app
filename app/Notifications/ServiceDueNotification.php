<?php

namespace App\Notifications;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ServiceDueNotification extends Notification
{
    use Queueable;

    public function __construct(public Vehicle $vehicle) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'vehicle_id' => $this->vehicle->id,
            'vehicle_name' => $this->vehicle->name,
            'message' => 'Service reminder for '.$this->vehicle->name,
            'due_date' => $this->vehicle->next_service_due_date?->toDateString(),
            'due_mileage' => $this->vehicle->next_service_due_mileage,
            'url' => route('vehicles.show', $this->vehicle),
        ];
    }
}
