@extends('layouts.customer')

@section('title', 'Dashboard - Customer Portal')

@section('content')
<div class="px-4">
    <!-- Welcome Section -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600">Kelola kendaraan dan servis Anda dengan mudah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">My Vehicles</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_vehicles'] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Need Service</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['vehicles_needing_service'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Upcoming Bookings</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['upcoming_bookings'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Repairs</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_repairs'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Reminder Alert -->
    @if($vehiclesNeedingService->count() > 0)
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-400 rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">⚠️ Service Reminder</h3>
                    <p class="text-gray-700 mb-3">{{ $vehiclesNeedingService->count() }} kendaraan Anda memerlukan servis segera!</p>
                    <div class="space-y-2">
                        @foreach($vehiclesNeedingService as $vehicle)
                            <div class="bg-white rounded-lg p-3 flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $vehicle->license_plate }} - {{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                    <p class="text-sm text-gray-600">
                                        Last oil change: {{ $vehicle->last_oil_change_date ? $vehicle->last_oil_change_date->format('d M Y') : 'Never' }}
                                        @if($vehicle->next_oil_change_date)
                                            | Due: {{ $vehicle->next_oil_change_date->format('d M Y') }}
                                            @if($vehicle->next_oil_change_date->isPast())
                                                <span class="text-red-600 font-semibold">(OVERDUE)</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('customer.bookings.create', ['vehicle' => $vehicle->id]) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm font-medium">
                                    Book Now
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Active Work Orders (Real-time Tracking) -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">🔧 Active Repairs (Real-time)</h3>
                @if($activeWorkOrders->count() > 0)
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full animate-pulse">
                        Live
                    </span>
                @endif
            </div>
            
            @if($activeWorkOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($activeWorkOrders as $workOrder)
                        <div class="border-2 border-blue-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $workOrder->booking->vehicle->license_plate }}</h4>
                                    <p class="text-sm text-gray-600">{{ $workOrder->booking->vehicle->brand }} {{ $workOrder->booking->vehicle->model }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($workOrder->status == 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($workOrder->status == 'in_progress') bg-blue-100 text-blue-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
                                </span>
                            </div>
                            
                            @if($workOrder->mechanic)
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Mechanic:</span> {{ $workOrder->mechanic->name }}
                                </p>
                            @endif
                            
                            @if($workOrder->total_cost > 0)
                                <p class="text-sm text-gray-600 mb-3">
                                    <span class="font-medium">Current Cost:</span> Rp {{ number_format($workOrder->total_cost, 0, ',', '.') }}
                                </p>
                            @endif
                            
                            <a href="{{ route('customer.bookings.show', $workOrder->booking) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                Track Progress →
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Tidak ada perbaikan yang sedang berlangsung</p>
                </div>
            @endif
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">📅 Upcoming Bookings</h3>
                <a href="{{ route('customer.bookings.create') }}" class="px-3 py-1 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                    + New Booking
                </a>
            </div>
            
            @if($upcomingBookings->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingBookings as $booking)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $booking->vehicle->license_plate }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->booking_date->format('d M Y') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($booking->status == 'confirmed') bg-green-100 text-green-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>Tidak ada booking mendatang</p>
                </div>
            @endif
        </div>
    </div>

    <!-- My Vehicles & Recent Service History -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Vehicles -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">🚗 My Vehicles</h3>
                <a href="{{ route('customer.vehicles.create') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                    + Add Vehicle
                </a>
            </div>
            
            @if($vehicles->count() > 0)
                <div class="space-y-3">
                    @foreach($vehicles as $vehicle)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $vehicle->license_plate }}</h4>
                                    <p class="text-sm text-gray-600">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                                    <p class="text-xs text-gray-500 mt-1">Odometer: {{ number_format($vehicle->current_odometer) }} km</p>
                                </div>
                                @if($vehicle->needsOilChange())
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">
                                        Service Due
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                        OK
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p class="text-gray-500 mb-3">Belum ada kendaraan terdaftar</p>
                    <a href="{{ route('customer.vehicles.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Tambah Kendaraan
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Service History -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">📋 Recent Service History</h3>
                <a href="{{ route('customer.service-history.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                    View All →
                </a>
            </div>
            
            @if($recentServices->count() > 0)
                <div class="space-y-3">
                    @foreach($recentServices as $service)
                        <div class="border-l-4 border-green-500 pl-3 py-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $service->booking->vehicle->license_plate }}</p>
                                    <p class="text-sm text-gray-600">{{ $service->completed_at->format('d M Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ $service->items->count() }} item(s)</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Belum ada riwayat servis</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
