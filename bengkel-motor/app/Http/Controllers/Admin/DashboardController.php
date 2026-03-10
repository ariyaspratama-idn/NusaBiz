<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\WorkOrder;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_mechanics' => User::where('role', 'mechanic')->count(),
            'bookings_today' => Booking::whereDate('booking_date', today())->count(),
            'active_work_orders' => WorkOrder::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_today' => WorkOrder::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'revenue_today' => WorkOrder::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->sum('total_cost'),
            'low_stock_items' => SparePart::whereRaw('stock <= min_stock')->count(),
        ];

        // Recent bookings
        $recent_bookings = Booking::with(['user', 'vehicle'])
            ->latest()
            ->take(5)
            ->get();

        // Recent work orders
        $recent_work_orders = WorkOrder::with(['booking.user', 'booking.vehicle', 'mechanic'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly revenue chart data (last 6 months)
        $monthly_revenue = WorkOrder::where('status', 'completed')
            ->where('completed_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->completed_at)->format('Y-m');
            })
            ->map(function ($row) {
                return [
                    'month' => \Carbon\Carbon::parse($row->first()->completed_at)->format('Y-m'),
                    'revenue' => $row->sum('total_cost'),
                ];
            })
            ->values();

        return view('admin.dashboard', compact(
            'stats',
            'recent_bookings',
            'recent_work_orders',
            'monthly_revenue'
        ));
    }
}
