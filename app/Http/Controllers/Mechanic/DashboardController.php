<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $mechanic = auth()->user();
        
        // Get work orders assigned to this mechanic
        $todayWorkOrders = WorkOrder::where('mechanic_id', $mechanic->id)
            ->whereDate('created_at', today())
            ->with(['booking.user', 'booking.vehicle'])
            ->get();

        $stats = [
            'total_today' => $todayWorkOrders->count(),
            'pending' => $todayWorkOrders->where('status', 'pending')->count(),
            'in_progress' => $todayWorkOrders->where('status', 'in_progress')->count(),
            'completed_today' => $todayWorkOrders->where('status', 'completed')->count(),
        ];

        // Get all active work orders
        $activeWorkOrders = WorkOrder::where('mechanic_id', $mechanic->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['booking.user', 'booking.vehicle'])
            ->latest()
            ->get();

        return view('mechanic.dashboard', compact('stats', 'activeWorkOrders'));
    }
}

