<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = auth()->user();
        
        // Get customer vehicles
        $vehicles = Vehicle::where('user_id', $customer->id)->get();
        
        // Check for vehicles needing oil change soon
        $vehiclesNeedingService = $vehicles->filter(function($vehicle) {
            return $vehicle->needsOilChange();
        });
        
        // Get upcoming bookings
        $upcomingBookings = Booking::where('user_id', $customer->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('booking_date', '>=', today())
            ->with('vehicle')
            ->latest('booking_date')
            ->get();
        
        // Get active work orders
        $activeWorkOrders = WorkOrder::whereHas('booking', function($query) use ($customer) {
                $query->where('user_id', $customer->id);
            })
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['booking.vehicle', 'mechanic'])
            ->latest()
            ->get();
        
        // Get recent service history
        $recentServices = WorkOrder::whereHas('booking', function($query) use ($customer) {
                $query->where('user_id', $customer->id);
            })
            ->where('status', 'completed')
            ->with(['booking.vehicle', 'items'])
            ->latest('completed_at')
            ->take(5)
            ->get();

        $stats = [
            'total_vehicles' => $vehicles->count(),
            'vehicles_needing_service' => $vehiclesNeedingService->count(),
            'upcoming_bookings' => $upcomingBookings->count(),
            'active_repairs' => $activeWorkOrders->count(),
        ];

        return view('customer.dashboard', compact(
            'vehicles',
            'vehiclesNeedingService',
            'upcomingBookings',
            'activeWorkOrders',
            'recentServices',
            'stats'
        ));
    }
}

