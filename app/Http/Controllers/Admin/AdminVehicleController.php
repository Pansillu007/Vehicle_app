<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AdminVehicleController extends Controller
{
    // Display the admin vehicle list with optional search support.
    public function index(Request $request)
    {
        $vehicles = Vehicle::query()
            ->with('user')
            ->when($request->search, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('number_plate', 'like', "%{$search}%")
                        ->orWhere('make', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.vehicles.index', compact('vehicles'));
    }
}
