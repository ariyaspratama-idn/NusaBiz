@extends('layouts.admin')

@section('title', 'Print Membership Card')

@section('content')
<div class="px-4 max-w-4xl mx-auto">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Membership Card</h2>
        <p class="text-gray-600">{{ $customer->name }}</p>
    </div>

    <!-- Printable Card -->
    <div id="membershipCard" class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-2xl p-8 mb-6 text-white" style="width: 600px; height: 350px; margin: 0 auto;">
        <div class="flex flex-col h-full justify-between">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold mb-1">BENGKEL MOTOR</h1>
                <p class="text-purple-200 text-sm">Premium Member Card</p>
            </div>

            <!-- Member Info -->
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-purple-200 text-sm mb-1">Member Name</p>
                    <p class="text-2xl font-bold mb-4">{{ $customer->name }}</p>
                    
                    <p class="text-purple-200 text-sm mb-1">Member ID</p>
                    <p class="text-xl font-mono font-bold tracking-wider">{{ $customer->membership_barcode }}</p>
                </div>

                <!-- Barcode -->
                <div class="bg-white p-3 rounded-lg">
                    <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode" style="width: 200px; height: 80px;">
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between text-xs text-purple-200">
                <span>Member since {{ $customer->created_at->format('M Y') }}</span>
                <span>Valid until {{ $customer->created_at->addYears(5)->format('M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Contact Info (Below Card) -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6" style="width: 600px; margin: 0 auto;">
        <h3 class="font-semibold text-gray-800 mb-3">Contact Information</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-medium">{{ $customer->email }}</p>
            </div>
            <div>
                <p class="text-gray-500">Phone</p>
                <p class="font-medium">{{ $customer->phone }}</p>
            </div>
            @if($customer->address)
            <div class="col-span-2">
                <p class="text-gray-500">Address</p>
                <p class="font-medium">{{ $customer->address }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-center space-x-4 mb-6">
        <button onclick="window.print()" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition font-semibold">
            🖨️ Print Card
        </button>
        <a href="{{ route('admin.customers.show', $customer) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            ← Back to Customer
        </a>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #membershipCard, #membershipCard * {
        visibility: visible;
    }
    #membershipCard {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endsection
