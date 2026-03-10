@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Customers -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Customers</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_customers'] }}</p>
            </div>
            <div class="bg-purple-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Bookings Today -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Bookings Today</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['bookings_today'] }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Work Orders -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Work Orders</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_work_orders'] }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Revenue Today -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Revenue Today</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Bookings</h3>
        @if($recent_bookings->count() > 0)
            <div class="space-y-3">
                @foreach($recent_bookings as $booking)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $booking->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->vehicle->license_plate }} - {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700">{{ $booking->booking_date->format('d M Y') }}</p>
                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                @if($booking->status == 'confirmed') bg-green-100 text-green-700
                                @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No recent bookings</p>
        @endif
    </div>

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Low Stock Alert</h3>
            @if($stats['low_stock_items'] > 0)
                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">
                    {{ $stats['low_stock_items'] }} items
                </span>
            @endif
        </div>
        @if($stats['low_stock_items'] > 0)
            <p class="text-gray-600 mb-3">You have {{ $stats['low_stock_items'] }} spare parts running low on stock.</p>
            <a href="{{ route('admin.spare-parts.index', ['low_stock' => 1]) }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                View Low Stock Items
            </a>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500">All spare parts are well stocked!</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.customers.create') }}" class="p-4 border-2 border-purple-200 rounded-lg hover:bg-purple-50 transition text-center group">
            <svg class="w-10 h-10 text-purple-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700">Add Customer</p>
        </a>

        <a href="{{ route('admin.bookings.create') }}" class="p-4 border-2 border-blue-200 rounded-lg hover:bg-blue-50 transition text-center group">
            <svg class="w-10 h-10 text-blue-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700">New Booking</p>
        </a>

        <a href="{{ route('admin.vehicles.create') }}" class="p-4 border-2 border-green-200 rounded-lg hover:bg-green-50 transition text-center group">
            <svg class="w-10 h-10 text-green-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700">Add Vehicle</p>
        </a>

        <a href="{{ route('admin.reports.financial') }}" class="p-4 border-2 border-yellow-200 rounded-lg hover:bg-yellow-50 transition text-center group">
            <svg class="w-10 h-10 text-yellow-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700">View Reports</p>
        </a>
    </div>
</div>
@endsection
