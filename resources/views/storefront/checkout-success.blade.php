@extends('layouts.storefront')

@section('title', 'Pesanan Diterima - ' . $order->order_number)

@section('content')
<div class="bg-gray-50 py-16 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden text-center relative">
            <div class="bg-green-500 h-2 w-full absolute top-0 left-0"></div>
            
            <div class="p-10 md:p-14">
                <div class="w-24 h-24 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-5xl mx-auto mb-6 shadow-sm">
                    <i class="fa-solid fa-check"></i>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Terima Kasih, Pesanan Diterima!</h1>
                <p class="text-gray-500 text-lg mb-8">Nomor Resi Pemesanan: <span class="font-bold text-gray-800 bg-gray-100 px-3 py-1 rounded-lg">{{ $order->order_number }}</span></p>
                
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 text-left mb-8 max-w-xl mx-auto">
                    <h3 class="font-bold text-gray-800 border-b border-indigo-200 pb-3 mb-4 flex items-center gap-2"><i class="fa-solid fa-wallet text-primary"></i> Instruksi Pembayaran</h3>
                    
                    @if($order->payment_method == 'cod')
                        <p class="text-gray-600 mb-2">Anda memilih metode **Bayar di Tempat (COD)**.</p>
                        <p class="text-gray-600 font-medium">Mohon siapkan uang pas sejumlah <strong class="text-primary text-xl ml-1">Rp {{ number_format($order->total, 0, ',', '.') }}</strong> saat kurir tiba.</p>
                    @elseif($order->payment_method == 'transfer')
                        <p class="text-gray-600 mb-2">Silakan transfer uang sejumlah <strong class="text-primary text-xl ml-1">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></p>
                        <p class="text-gray-600 mb-4">ke salah satu rekening berikut:</p>
                        <div class="bg-white p-4 rounded-xl shadow-sm border space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-800">BCA</span>
                                <span class="text-gray-600 font-mono tracking-widest">1234-5678-90</span>
                            </div>
                            <div class="flex justify-between items-center border-t pt-2">
                                <span class="font-bold text-gray-800">Mandiri</span>
                                <span class="text-gray-600 font-mono tracking-widest">9876-5432-10</span>
                            </div>
                            <div class="text-xs text-center text-gray-400 mt-2 border-t pt-2">a.n PT NusaBiz Wave Project</div>
                        </div>
                    @else
                        <p class="text-gray-600">Sistem pembayaran via {{ strtoupper($order->payment_method) }} sedang menunggu konfirmasi pihak ke-3.</p>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('home') }}" class="px-8 py-4 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors w-full sm:w-auto">Kembali ke Beranda</a>
                    <a href="{{ route('storefront.catalog') }}" class="px-8 py-4 rounded-xl font-bold text-white bg-primary hover:bg-primary-dark shadow-lg shadow-indigo-200 transition-all w-full sm:w-auto flex items-center justify-center gap-2">Lanjut Belanja <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="bg-gray-50 border-t p-6 text-sm text-gray-500">
                Resi pesanan dan rincian belanja telah diamankan dalam sistem. Anda dapat mengecek status pesanan melalui admin jika Anda memiliki akses.
            </div>
        </div>
    </div>
</div>
@endsection
