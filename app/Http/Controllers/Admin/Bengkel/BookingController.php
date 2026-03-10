<?php

namespace App\Http\Controllers\Admin\Bengkel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'vehicle', 'workOrder']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $bookings = $query->latest('booking_date')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $vehicles = Vehicle::with('user')->get();
        
        return view('admin.bookings.create', compact('customers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'complaint' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['status'] = 'confirmed';

        Booking::create($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil ditambahkan');
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'vehicle', 'workOrder.mechanic', 'workOrder.items']);
        
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $vehicles = Vehicle::with('user')->get();
        
        return view('admin.bookings.edit', compact('booking', 'customers', 'vehicles'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'booking_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,confirmed,in_progress,completed,cancelled'],
            'complaint' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil diupdate');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus');
    }
}

