<?php

namespace App\Policies;

use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;

class ServiceRecordPolicy
{
    public function viewAny(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin() || $vehicle->user_id === $user->id;
    }

    public function view(User $user, Vehicle $vehicle, ServiceRecord $service): bool
    {
        return ($user->isAdmin() || $vehicle->user_id === $user->id)
            && $service->vehicle_id === $vehicle->id;
    }

    public function create(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin() || $vehicle->user_id === $user->id;
    }

    public function update(User $user, Vehicle $vehicle, ServiceRecord $service): bool
    {
        return ($user->isAdmin() || $vehicle->user_id === $user->id)
            && $service->vehicle_id === $vehicle->id;
    }

    public function delete(User $user, Vehicle $vehicle, ServiceRecord $service): bool
    {
        return ($user->isAdmin() || $vehicle->user_id === $user->id)
            && $service->vehicle_id === $vehicle->id;
    }
}
