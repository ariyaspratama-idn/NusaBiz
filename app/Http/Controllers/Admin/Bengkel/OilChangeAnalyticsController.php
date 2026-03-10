<?php

namespace App\Http\Controllers\Admin\Bengkel;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OilChangeAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonths(3));
        $endDate = $request->input('end_date', now());

        // Vehicles needing oil change
        $vehiclesNeedingService = Vehicle::whereNotNull('next_oil_change_date')
            ->where(function($query) {
                $query->whereDate('next_oil_change_date', '<=', now()->addDays(7))
                      ->orWhereDate('next_oil_change_date', '<', now());
            })
            ->with('user')
            ->get();

        // Overdue vehicles
        $overdueVehicles = Vehicle::whereNotNull('next_oil_change_date')
            ->whereDate('next_oil_change_date', '<', now())
            ->with('user')
            ->get();

        // Oil changes performed
        $oilChangesPerformed = ServiceHistory::where('oil_changed', true)
            ->whereBetween('service_date', [$startDate, $endDate])
            ->with(['vehicle.user'])
            ->latest('service_date')
            ->get();

        // Monthly oil change trend
        $monthlyTrend = ServiceHistory::where('oil_changed', true)
            ->whereBetween('service_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(service_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Average interval compliance
        $avgCompliance = ServiceHistory::where('oil_changed', true)
            ->whereBetween('service_date', [$startDate, $endDate])
            ->avg(DB::raw('DATEDIFF(service_date, oil_change_date)'));

        $stats = [
            'vehicles_needing_service' => $vehiclesNeedingService->count(),
            'overdue_vehicles' => $overdueVehicles->count(),
            'oil_changes_this_period' => $oilChangesPerformed->count(),
            'avg_compliance_days' => round($avgCompliance ?? 0),
        ];

        return view('admin.analytics.oil-change', compact(
            'vehiclesNeedingService',
            'overdueVehicles',
            'oilChangesPerformed',
            'monthlyTrend',
            'stats',
            'startDate',
            'endDate'
        ));
    }
}

