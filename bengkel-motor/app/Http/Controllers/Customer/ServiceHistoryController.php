<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;

class ServiceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceHistory::whereHas('vehicle', function($q) {
            $q->where('user_id', auth()->id());
        })->with(['vehicle', 'workOrder.items']);

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        $serviceHistories = $query->latest('service_date')->paginate(15);

        $vehicles = \App\Models\Vehicle::where('user_id', auth()->id())->get();

        return view('customer.service-history.index', compact('serviceHistories', 'vehicles'));
    }

    public function show(ServiceHistory $serviceHistory)
    {
        if ($serviceHistory->vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $serviceHistory->load([
            'vehicle',
            'workOrder.items.service',
            'workOrder.items.sparePart',
            'workOrder.mechanic',
            'workOrder.progress'
        ]);

        return view('customer.service-history.show', compact('serviceHistory'));
    }
}
