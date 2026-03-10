<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Http\Request;

class BengkelBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['vehicle', 'workOrder'])
            ->latest('booking_date')
            ->paginate(15);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('user_id', auth()->id())->get();
        $services = Service::where('is_active', true)->get();

        if ($vehicles->isEmpty()) {
            return redirect()->route('customer.vehicles.create')
                ->with('error', 'Silakan tambahkan kendaraan terlebih dahulu');
        }

        return view('customer.bookings.create', compact('vehicles', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'complaint' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        // Verify vehicle belongs to customer
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Booking::create($validated);

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibuat. Kami akan segera mengkonfirmasi.');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load([
            'vehicle',
            'workOrder.mechanic',
            'workOrder.items.service',
            'workOrder.items.sparePart',
            'workOrder.progress.user'
        ]);

        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan');
    }
}

