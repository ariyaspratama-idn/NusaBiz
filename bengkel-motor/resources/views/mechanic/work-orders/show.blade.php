@extends('layouts.mechanic')

@section('title', 'Work Order Detail - Mechanic Panel')

@section('content')
<div class="px-4">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Work Order #{{ $workOrder->id }}</h2>
            <p class="text-gray-600">{{ $workOrder->booking->vehicle->license_plate }} - {{ $workOrder->booking->vehicle->brand }} {{ $workOrder->booking->vehicle->model }}</p>
        </div>
        <div>
            <span class="px-4 py-2 rounded-lg font-semibold
                @if($workOrder->status == 'pending') bg-yellow-100 text-yellow-700
                @elseif($workOrder->status == 'in_progress') bg-blue-100 text-blue-700
                @elseif($workOrder->status == 'completed') bg-green-100 text-green-700
                @else bg-gray-100 text-gray-700
                @endif">
                {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Vehicle & Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium">{{ $workOrder->booking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium">{{ $workOrder->booking->user->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $workOrder->booking->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Information</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500">License Plate</p>
                        <p class="font-medium">{{ $workOrder->booking->vehicle->license_plate }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Brand & Model</p>
                        <p class="font-medium">{{ $workOrder->booking->vehicle->brand }} {{ $workOrder->booking->vehicle->model }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Year</p>
                        <p class="font-medium">{{ $workOrder->booking->vehicle->year }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Current Odometer</p>
                        <p class="font-medium">{{ number_format($workOrder->booking->vehicle->current_odometer) }} km</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Last Oil Change</p>
                        <p class="font-medium">
                            @if($workOrder->booking->vehicle->last_oil_change_date)
                                {{ $workOrder->booking->vehicle->last_oil_change_date->format('d M Y') }}
                                ({{ number_format($workOrder->booking->vehicle->last_oil_change_km) }} km)
                            @else
                                <span class="text-gray-400">Not recorded</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Complaint -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Complaint</h3>
                <p class="text-gray-700">{{ $workOrder->booking->complaint ?? 'No complaint specified' }}</p>
            </div>

            <!-- Start Work Button -->
            @if($workOrder->status == 'pending')
                <form method="POST" action="{{ route('mechanic.work-orders.start', $workOrder) }}">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:shadow-lg transition font-semibold">
                        Start Work Order
                    </button>
                </form>
            @endif
        </div>

        <!-- Right Column - Work Details -->
        <div class="lg:col-span-2 space-y-6">
            @if($workOrder->status != 'pending')
                <!-- Add Progress -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Progress Update</h3>
                    <form method="POST" action="{{ route('mechanic.work-orders.add-progress', $workOrder) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe what you've done..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photo (Optional)</label>
                            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Max 5MB. Supports JPG, PNG</p>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Add Progress
                        </button>
                    </form>
                </div>

                <!-- Progress History -->
                @if($workOrder->progress->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress History</h3>
                        <div class="space-y-4">
                            @foreach($workOrder->progress as $progress)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-gray-800">{{ $progress->description }}</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $progress->user->name }} - {{ $progress->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        @if($progress->photo_path)
                                            <a href="{{ Storage::url($progress->photo_path) }}" target="_blank" class="ml-4">
                                                <img src="{{ Storage::url($progress->photo_path) }}" alt="Progress photo" class="w-20 h-20 object-cover rounded-lg">
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add Service/Spare Part -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Service / Spare Part</h3>
                    <form method="POST" action="{{ route('mechanic.work-orders.add-item', $workOrder) }}" id="addItemForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <select name="type" id="itemType" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="updateItemOptions()">
                                    <option value="service">Service</option>
                                    <option value="spare_part">Spare Part</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                                <select name="item_id" id="itemSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-type="service" data-price="{{ $service->price }}">
                                            {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                    @foreach($spareParts as $part)
                                        <option value="{{ $part->id }}" data-type="spare_part" data-price="{{ $part->price }}" style="display:none;">
                                            {{ $part->name }} - Rp {{ number_format($part->price, 0, ',', '.') }} (Stock: {{ $part->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <input type="number" name="quantity" value="1" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Add Item
                        </button>
                    </form>
                </div>

                <!-- Items List -->
                @if($workOrder->items->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Items Used</h3>
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($workOrder->items as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            {{ $item->service ? $item->service->name : $item->sparePart->name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $item->service ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                {{ $item->service ? 'Service' : 'Spare Part' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-50 font-bold">
                                    <td colspan="4" class="px-4 py-3 text-right">Total:</td>
                                    <td class="px-4 py-3">Rp {{ number_format($workOrder->total_cost, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Complete Work Order (MANDATORY OIL CHANGE TRACKING) -->
                @if($workOrder->status == 'in_progress')
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-500 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Complete Work Order
                        </h3>
                        
                        <form method="POST" action="{{ route('mechanic.work-orders.complete', $workOrder) }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis <span class="text-red-500">*</span></label>
                                <textarea name="diagnosis" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="What was the problem?"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Work Done <span class="text-red-500">*</span></label>
                                <textarea name="work_done" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="What repairs/services were performed?"></textarea>
                            </div>

                            <!-- MANDATORY ODOMETER READING -->
                            <div class="mb-4 bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4">
                                <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Current Odometer Reading (MANDATORY) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="odometer_reading" required min="{{ $workOrder->booking->vehicle->current_odometer }}" class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 font-semibold" placeholder="e.g., 15000">
                                <p class="text-xs text-gray-600 mt-1">Current: {{ number_format($workOrder->booking->vehicle->current_odometer) }} km</p>
                            </div>

                            <!-- OIL CHANGE TRACKING -->
                            <div class="mb-4 bg-blue-50 border-2 border-blue-400 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <input type="checkbox" name="oil_changed" id="oilChanged" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" onchange="toggleOilChangeDate()">
                                    <label for="oilChanged" class="ml-2 text-sm font-bold text-gray-800">
                                        Oil Changed During This Service?
                                    </label>
                                </div>
                                
                                <div id="oilChangeDateField" style="display:none;">
                                    <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Oil Change Date (MANDATORY if oil changed) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="oil_change_date" id="oilChangeDate" max="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border-2 border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    <p class="text-xs text-blue-700 mt-2 font-medium">
                                        ⚙️ Next oil change will be auto-calculated based on vehicle interval ({{ $workOrder->booking->vehicle->oil_change_interval_months }} month{{ $workOrder->booking->vehicle->oil_change_interval_months > 1 ? 's' : '' }})
                                    </p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                                <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Any additional notes..."></textarea>
                            </div>

                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition font-bold text-lg">
                                ✓ Complete Work Order
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateItemOptions() {
    const type = document.getElementById('itemType').value;
    const select = document.getElementById('itemSelect');
    const options = select.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.dataset.type === type) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    
    // Select first visible option
    const firstVisible = Array.from(options).find(opt => opt.dataset.type === type);
    if (firstVisible) {
        select.value = firstVisible.value;
    }
}

function toggleOilChangeDate() {
    const checkbox = document.getElementById('oilChanged');
    const dateField = document.getElementById('oilChangeDateField');
    const dateInput = document.getElementById('oilChangeDate');
    
    if (checkbox.checked) {
        dateField.style.display = 'block';
        dateInput.required = true;
        dateInput.value = '{{ date("Y-m-d") }}'; // Set to today by default
    } else {
        dateField.style.display = 'none';
        dateInput.required = false;
        dateInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateItemOptions();
});
</script>
@endpush
@endsection
