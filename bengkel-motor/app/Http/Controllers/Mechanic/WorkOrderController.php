<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderProgress;
use App\Models\WorkOrderItem;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index()
    {
        $mechanic = auth()->user();
        
        $workOrders = WorkOrder::where('mechanic_id', $mechanic->id)
            ->with(['booking.user', 'booking.vehicle', 'items'])
            ->latest()
            ->paginate(15);

        return view('mechanic.work-orders.index', compact('workOrders'));
    }

    public function show(WorkOrder $workOrder)
    {
        // Ensure mechanic can only view their own work orders
        if ($workOrder->mechanic_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $workOrder->load([
            'booking.user',
            'booking.vehicle',
            'items.service',
            'items.sparePart',
            'progress.user'
        ]);

        $services = Service::where('is_active', true)->get();
        $spareParts = SparePart::where('stock', '>', 0)->get();

        return view('mechanic.work-orders.show', compact('workOrder', 'services', 'spareParts'));
    }

    public function start(WorkOrder $workOrder)
    {
        if ($workOrder->mechanic_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $workOrder->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        // Update booking status
        $workOrder->booking->update(['status' => 'in_progress']);

        return redirect()->route('mechanic.work-orders.show', $workOrder)
            ->with('success', 'Work order started');
    }

    public function addProgress(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->mechanic_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'], // 5MB max
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('work-order-progress', 'public');
        }

        WorkOrderProgress::create([
            'work_order_id' => $workOrder->id,
            'user_id' => auth()->id(),
            'description' => $validated['description'],
            'photo_path' => $photoPath,
        ]);

        return back()->with('success', 'Progress updated successfully');
    }

    public function addItem(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->mechanic_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:service,spare_part'],
            'item_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated, $workOrder) {
            if ($validated['type'] === 'service') {
                $service = Service::findOrFail($validated['item_id']);
                
                WorkOrderItem::create([
                    'work_order_id' => $workOrder->id,
                    'service_id' => $service->id,
                    'quantity' => $validated['quantity'],
                    'price' => $service->price,
                    'subtotal' => $service->price * $validated['quantity'],
                ]);
            } else {
                $sparePart = SparePart::findOrFail($validated['item_id']);
                
                // Check stock
                if ($sparePart->stock < $validated['quantity']) {
                    throw new \Exception('Insufficient stock');
                }
                
                WorkOrderItem::create([
                    'work_order_id' => $workOrder->id,
                    'spare_part_id' => $sparePart->id,
                    'quantity' => $validated['quantity'],
                    'price' => $sparePart->price,
                    'subtotal' => $sparePart->price * $validated['quantity'],
                ]);

                // Reduce stock
                $sparePart->decrement('stock', $validated['quantity']);
            }

            // Update total cost
            $workOrder->update([
                'total_cost' => $workOrder->items()->sum('subtotal'),
            ]);
        });

        return back()->with('success', 'Item added successfully');
    }

    public function complete(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->mechanic_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'diagnosis' => ['required', 'string'],
            'work_done' => ['required', 'string'],
            'odometer_reading' => ['required', 'integer', 'min:0'],
            'oil_changed' => ['required', 'boolean'],
            'oil_change_date' => ['required_if:oil_changed,1', 'nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $workOrder, $request) {
            // Update work order
            $workOrder->update([
                'status' => 'completed',
                'completed_at' => now(),
                'diagnosis' => $validated['diagnosis'],
                'work_done' => $validated['work_done'],
            ]);

            // Update booking
            $workOrder->booking->update(['status' => 'completed']);

            // Create service history
            $vehicle = $workOrder->booking->vehicle;
            
            $serviceHistoryData = [
                'vehicle_id' => $vehicle->id,
                'work_order_id' => $workOrder->id,
                'service_date' => now(),
                'odometer_reading' => $validated['odometer_reading'],
                'oil_changed' => $validated['oil_changed'],
                'notes' => $validated['notes'],
            ];

            // If oil was changed, calculate next oil change
            if ($validated['oil_changed']) {
                $oilChangeDate = $validated['oil_change_date'];
                $intervalMonths = $vehicle->oil_change_interval_months ?? 1;
                
                $nextOilChangeDate = \Carbon\Carbon::parse($oilChangeDate)
                    ->addMonths($intervalMonths);

                $serviceHistoryData['oil_change_km'] = $validated['odometer_reading'];
                $serviceHistoryData['oil_change_date'] = $oilChangeDate;
                $serviceHistoryData['next_oil_change_date'] = $nextOilChangeDate;

                // Update vehicle
                $vehicle->update([
                    'current_odometer' => $validated['odometer_reading'],
                    'last_oil_change_km' => $validated['odometer_reading'],
                    'last_oil_change_date' => $oilChangeDate,
                    'next_oil_change_date' => $nextOilChangeDate,
                    'first_reminder_sent_at' => null,
                    'second_reminder_sent_at' => null,
                ]);
            } else {
                // Just update odometer
                $vehicle->update([
                    'current_odometer' => $validated['odometer_reading'],
                ]);
            }

            ServiceHistory::create($serviceHistoryData);
        });

        return redirect()->route('mechanic.work-orders.index')
            ->with('success', 'Work order completed successfully');
    }
}
