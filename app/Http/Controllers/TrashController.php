<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

/**
 * Trash listing view only — restore and force-delete go through /api (Sanctum).
 */
class TrashController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $vehicleQuery = Vehicle::onlyTrashed()->with('user');
        $serviceQuery = ServiceRecord::onlyTrashed()->with('vehicle.user');

        if (! $user->isAdmin()) {
            $vehicleQuery->where('user_id', $user->id);
            $serviceQuery->whereHas('vehicle', fn ($q) => $q->where('user_id', $user->id));
        }

        $trashedVehicles = $vehicleQuery->latest('deleted_at')->paginate(10, ['*'], 'vehicles_page');
        $trashedServices = $serviceQuery->latest('deleted_at')->paginate(10, ['*'], 'services_page');

        return view('trash.index', compact('trashedVehicles', 'trashedServices'));
    }
}
