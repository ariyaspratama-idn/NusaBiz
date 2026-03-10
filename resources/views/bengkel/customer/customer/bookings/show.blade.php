@extends('layouts.customer')

@section('title', 'Booking Detail - Customer Portal')

@section('content')
<div class="px-4">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Booking #{{ $booking->id }}</h2>
            <p class="text-gray-600">{{ $booking->vehicle->license_plate }} - {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
        </div>
        <div>
            <span class="px-4 py-2 rounded-lg font-semibold text-sm
                @if($booking->status == 'pending') bg-yellow-100 text-yellow-700
                @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-700
                @elseif($booking->status == 'in_progress') bg-orange-100 text-orange-700
                @elseif($booking->status == 'completed') bg-green-100 text-green-700
                @elseif($booking->status == 'cancelled') bg-red-100 text-red-700
                @else bg-gray-100 text-gray-700
                @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Booking Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Booking Details -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Details</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Booking Date</p>
                        <p class="font-medium">{{ $booking->booking_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="font-medium">{{ $booking->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($booking->workOrder && $booking->workOrder->mechanic)
                        <div>
                            <p class="text-sm text-gray-500">Assigned Mechanic</p>
                            <p class="font-medium">{{ $booking->workOrder->mechanic->name }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Complaint -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Complaint</h3>
                <p class="text-gray-700">{{ $booking->complaint }}</p>
                @if($booking->notes)
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Additional Notes:</p>
                        <p class="text-gray-700">{{ $booking->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Cancel Button -->
            @if(in_array($booking->status, ['pending', 'confirmed']))
                <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        Cancel Booking
                    </button>
                </form>
            @endif
        </div>

        <!-- Right Column - Work Order Progress (Real-time Tracking) -->
        <div class="lg:col-span-2 space-y-6">
            @if($booking->workOrder)
                <!-- Real-time Status -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-400 rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Real-time Work Progress
                        </h3>
                        @if($booking->workOrder->status == 'in_progress')
                            <span class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-full animate-pulse">
                                🔴 Live
                            </span>
                        @endif
                    </div>

                    <!-- Progress Timeline -->
                    @if($booking->workOrder->progress->count() > 0)
                        <div class="space-y-4">
                            @foreach($booking->workOrder->progress as $progress)
                                <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-gray-800 mb-1">{{ $progress->description }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $progress->user->name }} - {{ $progress->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        @if($progress->photo_path)
                                            <a href="{{ Storage::url($progress->photo_path) }}" target="_blank" class="ml-4">
                                                <img src="{{ Storage::url($progress->photo_path) }}" alt="Progress photo" class="w-24 h-24 object-cover rounded-lg hover:scale-105 transition">
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500">Work has not started yet</p>
                        </div>
                    @endif
                </div>

                <!-- Services & Parts Used -->
                @if($booking->workOrder->items->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Services & Parts</h3>
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
                                @foreach($booking->workOrder->items as $item)
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
                                    <td colspan="4" class="px-4 py-3 text-right">Total Cost:</td>
                                    <td class="px-4 py-3 text-lg">Rp {{ number_format($booking->workOrder->total_cost, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Completion Details -->
                @if($booking->workOrder->status == 'completed')
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-500 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Work Completed
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Diagnosis:</p>
                                <p class="text-gray-800">{{ $booking->workOrder->diagnosis }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Work Done:</p>
                                <p class="text-gray-800">{{ $booking->workOrder->work_done }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Completed At:</p>
                                <p class="text-gray-800">{{ $booking->workOrder->completed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- No Work Order Yet -->
                <div class="bg-white rounded-xl shadow-md p-6 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Waiting for Confirmation</h3>
                    <p class="text-gray-600">Your booking is being reviewed. We'll notify you once it's confirmed and a mechanic is assigned.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
