<?php

namespace App\Http\Controllers\Admin\Bengkel;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BengkelReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function financial(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Revenue summary
        $revenue = WorkOrder::where('status', 'completed')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->sum('total_cost');

        // Daily revenue
        $dailyRevenue = WorkOrder::where('status', 'completed')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(completed_at) as date'),
                DB::raw('SUM(total_cost) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top services
        $topServices = DB::table('work_order_items')
            ->join('services', 'work_order_items.service_id', '=', 'services.id')
            ->join('work_orders', 'work_order_items.work_order_id', '=', 'work_orders.id')
            ->where('work_orders.status', 'completed')
            ->whereBetween('work_orders.completed_at', [$startDate, $endDate])
            ->select(
                'services.name',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(work_order_items.subtotal) as revenue')
            )
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return view('admin.reports.financial', compact(
            'revenue',
            'dailyRevenue',
            'topServices',
            'startDate',
            'endDate'
        ));
    }

    public function workOrders(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $workOrders = WorkOrder::with(['booking.user', 'booking.vehicle', 'mechanic'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $stats = [
            'total' => $workOrders->count(),
            'completed' => $workOrders->where('status', 'completed')->count(),
            'in_progress' => $workOrders->where('status', 'in_progress')->count(),
            'pending' => $workOrders->where('status', 'pending')->count(),
        ];

        return view('admin.reports.work-orders', compact(
            'workOrders',
            'stats',
            'startDate',
            'endDate'
        ));
    }

    public function inventory()
    {
        $spareParts = \App\Models\SparePart::all();

        $stats = [
            'total_items' => $spareParts->count(),
            'low_stock' => $spareParts->filter(fn($p) => $p->isLowStock())->count(),
            'total_value' => $spareParts->sum(fn($p) => $p->stock * $p->cost_price),
        ];

        return view('admin.reports.inventory', compact('spareParts', 'stats'));
    }
}

