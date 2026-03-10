@extends('layouts.admin')

@section('title', 'Customers - Admin Panel')
@section('page-title', 'Customer Management')

@section('header-actions')
    <a href="{{ route('admin.customers.create') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition">
        + Add Customer
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Search -->
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search by name, email, phone, or barcode..." 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
            >
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700 transition">
                Search
            </button>
        </form>

        <!-- Barcode Scanner -->
        <div class="flex items-center space-x-2">
            <button 
                onclick="openBarcodeScanner()" 
                class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:shadow-lg transition flex items-center justify-center"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                Scan Barcode
            </button>
        </div>
    </div>
</div>

<!-- Customer Table -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership Barcode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicles</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $customer->phone }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($customer->address, 30) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($customer->membership_barcode)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $customer->membership_barcode }}
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Not Generated
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $customer->vehicles->count() }} vehicle(s)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        @if($customer->membership_barcode)
                            <a href="{{ route('admin.customers.barcode', $customer) }}" class="text-green-600 hover:text-green-900" target="_blank">Print Card</a>
                        @endif
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p>No customers found</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="px-6 py-4 bg-gray-50">
        {{ $customers->links() }}
    </div>
</div>

<!-- Barcode Scanner Modal -->
<div id="barcodeScannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Scan Customer Barcode</h3>
            <button onclick="closeBarcodeScanner()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div id="reader" class="mb-4"></div>
        
        <form method="POST" action="{{ route('admin.customers.scan') }}" id="barcodeForm">
            @csrf
            <input type="hidden" name="barcode" id="barcodeInput">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Or enter barcode manually:</p>
                <input 
                    type="text" 
                    id="manualBarcode" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 mb-3"
                    placeholder="BM-YYYYMMXXX"
                >
                <button type="button" onclick="submitManualBarcode()" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrcodeScanner;

function openBarcodeScanner() {
    document.getElementById('barcodeScannerModal').classList.remove('hidden');
    document.getElementById('barcodeScannerModal').classList.add('flex');
    
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { fps: 10, qrbox: 250 }
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanError);
}

function closeBarcodeScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
    document.getElementById('barcodeScannerModal').classList.add('hidden');
    document.getElementById('barcodeScannerModal').classList.remove('flex');
}

function onScanSuccess(decodedText, decodedResult) {
    document.getElementById('barcodeInput').value = decodedText;
    document.getElementById('barcodeForm').submit();
}

function onScanError(errorMessage) {
    // Handle scan error
}

function submitManualBarcode() {
    const barcode = document.getElementById('manualBarcode').value;
    if (barcode) {
        document.getElementById('barcodeInput').value = barcode;
        document.getElementById('barcodeForm').submit();
    }
}
</script>
@endpush
