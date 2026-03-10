@extends('layouts.customer')

@section('title', 'Create Booking - Customer Portal')

@section('content')
<div class="px-4 max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Book Service Appointment</h2>
        <p class="text-gray-600">Jadwalkan servis kendaraan Anda dengan mudah</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('customer.bookings.store') }}">
            @csrf
            
            <!-- Vehicle Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Vehicle <span class="text-red-500">*</span>
                </label>
                <select name="vehicle_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Select Vehicle --</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->license_plate }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                            @if($vehicle->needsOilChange())
                                - ⚠️ Service Due
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Booking Date -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Preferred Date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="booking_date" 
                    value="{{ old('booking_date', date('Y-m-d')) }}" 
                    min="{{ date('Y-m-d') }}"
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
                @error('booking_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Complaint/Issue -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Complaint / Issue Description <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="complaint" 
                    rows="4" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Describe the problem or service you need..."
                >{{ old('complaint') }}</textarea>
                @error('complaint')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Additional Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Additional Notes (Optional)
                </label>
                <textarea 
                    name="notes" 
                    rows="3" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Any additional information..."
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Service Suggestions -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-2">💡 Common Services</h4>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    @foreach($services->take(6) as $service)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $service->name }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-600 mt-2">* Final services will be determined after inspection</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center space-x-4">
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition font-semibold"
                >
                    Submit Booking
                </button>
                <a 
                    href="{{ route('customer.dashboard') }}" 
                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
        <h4 class="font-semibold text-gray-800 mb-2">📌 What happens next?</h4>
        <ol class="list-decimal list-inside space-y-1 text-sm text-gray-700">
            <li>Your booking will be reviewed by our team</li>
            <li>We'll confirm your appointment within 24 hours</li>
            <li>You'll receive a notification when confirmed</li>
            <li>Track your repair progress in real-time on your dashboard</li>
        </ol>
    </div>
</div>
@endsection
