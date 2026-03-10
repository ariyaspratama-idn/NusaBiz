@extends('layouts.storefront')

@section('title', 'Keranjang & Pembayaran')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Checkout Pesanan</h1>
        
        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Left Column (Form) -->
                <div class="w-full lg:w-3/5 space-y-6">
                    
                    @if(session('error') || $errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0"><i class="fa-solid fa-circle-exclamation text-red-500"></i></div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada isian Anda:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @if(session('error')) <li>{{ session('error') }}</li> @endif
                                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Data Diri Form -->
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2 border-b pb-4">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-black">1</span> 
                            Data Penerima
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="customer_name" required value="{{ old('customer_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400" placeholder="Misal: Budi Santoso">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">No. WhatsApp *</label>
                                <input type="text" name="customer_phone" required value="{{ old('customer_phone') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400" placeholder="081234567890">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email (Opsional)</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400" placeholder="budi@example.com">
                            </div>
                        </div>
                    </div>

                    <!-- Alamat Pengiriman Form -->
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2 border-b pb-4">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-black">2</span> 
                            Alamat Pengiriman
                        </h2>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi *</label>
                                    <input type="text" name="shipping_province" required value="{{ old('shipping_province') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kota / Kabupaten *</label>
                                    <input type="text" name="shipping_city" required value="{{ old('shipping_city') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan *</label>
                                    <input type="text" name="shipping_district" required value="{{ old('shipping_district') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                                <textarea name="shipping_address" required rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400" placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW, Patokan">{{ old('shipping_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Ekspedisi & Pembayaran Form -->
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2 border-b pb-4">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-black">3</span> 
                            Pengiriman & Pembayaran
                        </h2>
                        
                        <div class="space-y-8">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Jenis Pengiriman *</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="shipping_type" value="nasional" class="peer sr-only" checked onclick="setShippingCost(20000)">
                                        <div class="p-4 rounded-xl border-2 peer-checked:border-primary peer-checked:bg-indigo-50 hover:bg-gray-50 transition-all text-center">
                                            <i class="fa-solid fa-truck-fast text-2xl mb-2 text-gray-400 peer-checked:text-primary"></i>
                                            <div class="font-bold text-gray-800">Kurir Nasional</div>
                                            <div class="text-xs text-gray-500 mt-1">(JNE, J&T, Sicepat)</div>
                                            <div class="text-primary font-bold mt-2">Rp 20.000</div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="shipping_type" value="ojek_online" class="peer sr-only" onclick="setShippingCost(15000)">
                                        <div class="p-4 rounded-xl border-2 peer-checked:border-primary peer-checked:bg-indigo-50 hover:bg-gray-50 transition-all text-center">
                                            <i class="fa-solid fa-motorcycle text-2xl mb-2 text-gray-400 peer-checked:text-primary"></i>
                                            <div class="font-bold text-gray-800">Ojek Online</div>
                                            <div class="text-xs text-gray-500 mt-1">(Gojek/Grab / Instan)</div>
                                            <div class="text-primary font-bold mt-2">Rp 15.000</div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="shipping_type" value="kurir_internal" class="peer sr-only" onclick="setShippingCost(0)">
                                        <div class="p-4 rounded-xl border-2 peer-checked:border-primary peer-checked:bg-indigo-50 hover:bg-gray-50 transition-all text-center">
                                            <i class="fa-solid fa-box-open text-2xl mb-2 text-gray-400 peer-checked:text-primary"></i>
                                            <div class="font-bold text-gray-800">Ambil di Toko</div>
                                            <div class="text-xs text-gray-500 mt-1">(Gratis Ongkir)</div>
                                            <div class="text-primary font-bold mt-2">Rp 0</div>
                                        </div>
                                    </label>
                                </div>
                                <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="20000">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Metode Pembayaran *</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="payment_method" value="transfer" class="peer sr-only" checked>
                                        <div class="p-4 rounded-xl border-2 peer-checked:border-primary peer-checked:bg-indigo-50 hover:bg-gray-50 transition-all flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-lg border flex flex-shrink-0 items-center justify-center text-primary text-xl shadow-sm"><i class="fa-solid fa-building-columns"></i></div>
                                            <div>
                                                <div class="font-bold text-gray-800">Transfer Bank</div>
                                                <div class="text-xs text-gray-500 mt-1">BCA, Mandiri, BNI, BRI</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="payment_method" value="cod" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 peer-checked:border-primary peer-checked:bg-indigo-50 hover:bg-gray-50 transition-all flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-lg border flex flex-shrink-0 items-center justify-center text-primary text-xl shadow-sm"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                                            <div>
                                                <div class="font-bold text-gray-800">Bayar di Tempat (COD)</div>
                                                <div class="text-xs text-gray-500 mt-1">Bayar saat kurir mengantar</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder-gray-400" placeholder="Contoh: Titip di pos satpam">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                
                <!-- Right Column (Order Summary) -->
                <div class="w-full lg:w-2/5 mt-8 lg:mt-0">
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                        <h2 class="text-xl font-bold mb-6 border-b pb-4">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-4 mb-6 max-h-80 overflow-y-auto pr-2 scrollbar-thin">
                            @php $subtotal = 0; @endphp
                            @foreach($cart as $key => $item)
                            @php 
                                $itemSubtotal = $item['price'] * $item['quantity'];
                                $subtotal += $itemSubtotal;
                            @endphp
                            <div class="flex gap-4 p-3 bg-gray-50 rounded-xl">
                                <div class="w-20 h-20 bg-white rounded-lg border overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    <i class="fa-solid fa-box text-gray-300 text-2xl"></i>
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-800 line-clamp-2 leading-snug">{{ $item['name'] }}</h4>
                                        @if(isset($item['variant']))
                                        <span class="text-xs text-gray-500 bg-gray-200 px-2 py-0.5 rounded-md mt-1 inline-block">{{ $item['variant'] }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-end justify-between mt-2">
                                        <span class="text-sm font-semibold text-gray-600">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                        <span class="text-sm font-bold text-primary">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 space-y-3 mb-6">
                            <div class="flex justify-between items-center text-sm font-medium text-gray-600">
                                <span>Subtotal Produk</span>
                                <span id="summary-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm font-medium text-gray-600">
                                <span>Estimasi Ongkos Kirim</span>
                                <span id="summary-shipping">Rp 20.000</span>
                            </div>
                            <div class="border-t pt-3 mt-3 flex justify-between items-center">
                                <span class="font-bold text-gray-800 text-lg">Total Pembayaran</span>
                                <span class="font-black text-primary text-2xl" id="summary-total" data-subtotal="{{ $subtotal }}">Rp {{ number_format($subtotal + 20000, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full h-14 bg-primary text-white font-bold text-lg rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
                            Buat Pesanan Sekarang <i class="fa-solid fa-arrow-right"></i>
                        </button>

                        <div class="mt-4 text-center text-xs text-gray-500">
                            <i class="fa-solid fa-shield-halved text-green-500 mr-1"></i> Transaksi dijamin aman dan terenkripsi
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function setShippingCost(cost) {
        document.getElementById('shipping_cost_input').value = cost;
        document.getElementById('summary-shipping').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(cost);
        
        const subtotal = parseInt(document.getElementById('summary-total').getAttribute('data-subtotal'));
        const total = subtotal + cost;
        
        document.getElementById('summary-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }
</script>
@endsection
