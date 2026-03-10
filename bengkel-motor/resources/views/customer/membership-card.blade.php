@extends('layouts.customer')

@section('title', 'My Membership Card - Customer Portal')

@section('content')
<div class="px-4 max-w-4xl mx-auto">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">My Membership Card</h2>
        <p class="text-gray-600">Show this card at the workshop for quick service</p>
    </div>

    <!-- Digital Membership Card -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl shadow-2xl p-8 mb-6 text-white" style="max-width: 600px; margin: 0 auto;">
        <div class="flex flex-col h-full justify-between" style="min-height: 300px;">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold mb-1">BENGKEL MOTOR</h1>
                <p class="text-purple-200 text-sm">Premium Member Card</p>
            </div>

            <!-- Member Info -->
            <div class="flex items-end justify-between mt-8">
                <div>
                    <p class="text-purple-200 text-sm mb-1">Member Name</p>
                    <p class="text-2xl font-bold mb-4">{{ Auth::user()->name }}</p>
                    
                    <p class="text-purple-200 text-sm mb-1">Member ID</p>
                    <p class="text-xl font-mono font-bold tracking-wider">{{ Auth::user()->membership_barcode }}</p>
                </div>

                <!-- Barcode -->
                @if(Auth::user()->membership_barcode)
                    <div class="bg-white p-3 rounded-lg">
                        <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode" style="width: 200px; height: 80px;">
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between text-xs text-purple-200 mt-6">
                <span>Member since {{ Auth::user()->created_at->format('M Y') }}</span>
                <span>Valid until {{ Auth::user()->created_at->addYears(5)->format('M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Benefits -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6" style="max-width: 600px; margin: 0 auto;">
        <h3 class="font-semibold text-gray-800 mb-4">✨ Membership Benefits</h3>
        <div class="space-y-3">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800">Priority Service</p>
                    <p class="text-sm text-gray-600">Skip the queue with your membership card</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800">Service History Tracking</p>
                    <p class="text-sm text-gray-600">All your service records in one place</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800">Oil Change Reminders</p>
                    <p class="text-sm text-gray-600">Never miss your scheduled maintenance</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800">Online Booking</p>
                    <p class="text-sm text-gray-600">Book your service appointment anytime</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Use -->
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-6" style="max-width: 600px; margin: 0 auto;">
        <h3 class="font-semibold text-gray-800 mb-3">📱 How to Use Your Card</h3>
        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
            <li>Show this screen to our staff when you arrive</li>
            <li>They will scan your barcode for quick check-in</li>
            <li>Your vehicle and service history will be instantly loaded</li>
            <li>Enjoy faster, personalized service!</li>
        </ol>
    </div>
</div>
@endsection
