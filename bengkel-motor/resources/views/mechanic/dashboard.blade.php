@extends('layouts.mechanic')

@section('title', 'Dashboard - Mechanic Panel')

@section('content')
<div class="px-4">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Mekanik</h2>
        <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Work Orders Today</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_today'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">In Progress</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Completed Today</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['completed_today'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Work Orders -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Active Work Orders</h3>
        
        @if($activeWorkOrders->count() > 0)
            <div class="space-y-4">
                @foreach($activeWorkOrders as $workOrder)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="font-semibold text-gray-800">
                                        {{ $workOrder->booking->vehicle->license_plate }}
                                    </h4>
                                    <span class="px-3 py-1 text-xs rounded-full font-medium
                                        @if($workOrder->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($workOrder->status == 'in_progress') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="font-medium">Customer:</span> {{ $workOrder->booking->user->name }}
                                </p>
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="font-medium">Vehicle:</span> 
                                    {{ $workOrder->booking->vehicle->brand }} {{ $workOrder->booking->vehicle->model }} ({{ $workOrder->booking->vehicle->year }})
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Complaint:</span> {{ $workOrder->booking->complaint ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('mechanic.work-orders.show', $workOrder) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                    @if($workOrder->status == 'pending')
                                        Start Work
                                    @else
                                        Continue
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p>Tidak ada work order yang aktif</p>
            </div>
        @endif
    </div>
</div>
@endsection
