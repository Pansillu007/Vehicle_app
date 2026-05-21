<?php

namespace App\Services;

use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\ServiceDueNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class ServiceReminderService
{
    public const DEFAULT_DAYS = 90;
    public const DEFAULT_MILES = 5000;

    public function updateVehicleSchedule(Vehicle $vehicle, ServiceRecord $record): void
    {
        $vehicle->update([
            'mileage' => max($vehicle->mileage, $record->mileage),
            'next_service_due_date' => Carbon::parse($record->service_date)->addDays(self::DEFAULT_DAYS),
            'next_service_due_mileage' => $record->mileage + self::DEFAULT_MILES,
        ]);
    }

    public function notifyDueServices(User $user): void
    {
        $vehicles = $user->vehicles()
            ->where(function ($q) {
                $q->where('next_service_due_date', '<=', now()->addDays(7))
                    ->orWhereRaw('mileage >= next_service_due_mileage - 500');
            })
            ->get();

        foreach ($vehicles as $vehicle) {
            $user->notify(new ServiceDueNotification($vehicle));
        }
    }
}
