@extends('layouts.admin')

@section('title', 'Oil Change Analytics - Admin Panel')
@section('page-title', 'Oil Change Analytics & Reports')

@section('content')
<!-- Date Filter -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.analytics.oil-change') }}" class="flex items-end space-x-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            Apply Filter
        </button>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Needing Service Soon</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['vehicles_needing_service'] }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Overdue Vehicles</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['overdue_vehicles'] }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Oil Changes (Period)</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['oil_changes_this_period'] }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Avg Compliance</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['avg_compliance_days'] }} days</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Vehicles Needing Service -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">⚠️ Vehicles Needing Service</h3>
        @if($vehiclesNeedingService->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($vehiclesNeedingService as $vehicle)
                    <div class="border-l-4 {{ $vehicle->next_oil_change_date->isPast() ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50' }} p-3 rounded">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $vehicle->license_plate }}</p>
                                <p class="text-sm text-gray-600">{{ $vehicle->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                <p class="text-xs {{ $vehicle->next_oil_change_date->isPast() ? 'text-red-600' : 'text-yellow-600' }} font-medium mt-1">
                                    Due: {{ $vehicle->next_oil_change_date->format('d M Y') }}
                                    @if($vehicle->next_oil_change_date->isPast())
                                        ({{ $vehicle->next_oil_change_date->diffForHumans() }})
                                    @endif
                                </p>
                            </div>
                            <a href="tel:{{ $vehicle->user->phone }}" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                Call
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">All vehicles are up to date!</p>
        @endif
    </div>

    <!-- Monthly Trend Chart -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Monthly Oil Change Trend</h3>
        @if($monthlyTrend->count() > 0)
            <div class="space-y-2">
                @foreach($monthlyTrend as $trend)
                    <div class="flex items-center">
                        <div class="w-24 text-sm text-gray-600">{{ \Carbon\Carbon::parse($trend->month . '-01')->format('M Y') }}</div>
                        <div class="flex-1 bg-gray-200 rounded-full h-6 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-full flex items-center justify-end px-2 text-white text-xs font-semibold" style="width: {{ ($trend->count / $monthlyTrend->max('count')) * 100 }}%">
                                {{ $trend->count }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No data available for this period</p>
        @endif
    </div>
</div>

<!-- Recent Oil Changes -->
<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">🛢️ Recent Oil Changes</h3>
    @if($oilChangesPerformed->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Odometer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Next Service</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($oilChangesPerformed->take(20) as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $service->service_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $service->vehicle->license_plate }}</div>
                                <div class="text-sm text-gray-500">{{ $service->vehicle->brand }} {{ $service->vehicle->model }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $service->vehicle->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($service->odometer_reading) }} km</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($service->next_oil_change_date)
                                    {{ $service->next_oil_change_date->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 text-center py-8">No oil changes in this period</p>
    @endif
</div>
@endsection
