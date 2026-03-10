@extends('layouts.storefront')

@section('title', 'Syarat & Ketentuan - NusaBiz')
@section('meta_description', 'Syarat dan Ketentuan layanan NusaBiz.')

@section('content')
<div class="bg-gray-50 py-12 border-b border-gray-200">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Syarat & Ketentuan Layanan</h1>
        <p class="text-gray-600 text-lg">Terakhir diperbarui: {{ date('d F Y') }}</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="prose prose-indigo max-w-none text-gray-700">
            {!! nl2br(e($content)) !!}

            <div class="mt-8 p-6 bg-indigo-50 border border-indigo-100 rounded-xl relative">
                <i class="fa-solid fa-circle-info absolute top-6 right-6 text-indigo-300 text-3xl"></i>
                <h4 class="font-bold text-indigo-900 mb-2 mt-0">Butuh bantuan lebih lanjut?</h4>
                <p class="text-indigo-800 text-sm mb-0">Jika ada pertanyaan atau komplain, silakan gunakan fitur <a href="#" onclick="event.preventDefault(); toggleChat()" class="font-bold underline hover:text-indigo-600">Live Chat</a> kami.</p>
            </div>
        </div>
    </div>
</div>
@endsection
