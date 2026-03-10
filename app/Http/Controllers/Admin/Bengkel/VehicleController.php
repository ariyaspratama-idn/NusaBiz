<?php

namespace App\Http\Controllers\Admin\Bengkel;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $vehicles = $query->latest()->paginate(15);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        return view('admin.vehicles.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles'],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:30'],
            'vin_number' => ['nullable', 'string', 'max:50'],
            'current_odometer' => ['required', 'integer', 'min:0'],
            'oil_change_interval_months' => ['required', 'integer', 'in:1,2'],
        ]);

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['user', 'serviceHistories.workOrder', 'bookings']);
        
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
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

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil diupdate');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus');
    }
}

