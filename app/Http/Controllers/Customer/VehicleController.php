<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('user_id', auth()->id())
            ->withCount('serviceHistories')
            ->get();

        return view('customer.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('customer.vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles'],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:30'],
            'vin_number' => ['nullable', 'string', 'max:50'],
            'current_odometer' => ['required', 'integer', 'min:0'],
            'oil_change_interval_months' => ['required', 'integer', 'in:1,2'],
        ]);

        $validated['user_id'] = auth()->id();

        Vehicle::create($validated);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function show(Vehicle $vehicle)
    {
        // Ensure customer can only view their own vehicles
        if ($vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $vehicle->load(['serviceHistories.workOrder.items', 'bookings.workOrder']);

        return view('customer.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate,' . $vehicle->id],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:30'],
            'vin_number' => ['nullable', 'string', 'max:50'],
            'current_odometer' => ['required', 'integer', 'min:0'],
            'oil_change_interval_months' => ['required', 'integer', 'in:1,2'],
        ]);

        $vehicle->update($validated);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Kendaraan berhasil diupdate');
    }
}

