@extends('layouts.storefront')

@section('title', 'Official Product Archive — NusaBiz')

@section('content')
<!-- High-End Promo Banner -->
<div class="bg-white relative overflow-hidden pt-12">
    <div class="hero-blob blob-1"></div>
    <div class="container mx-auto px-6 relative z-10 flex flex-col items-center text-center">
        <span class="hero-badge">Curated Collection 2024</span>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight leading-none">
            Jelajahi <span class="text-primary italic">Kreativitas</span> & <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Teknologi</span>
        </h1>
        <p class="text-lg text-slate-500 max-w-2xl mb-12 font-medium">
            Temukan kurasi produk premium yang dirancang untuk meningkatkan gaya hidup dan produktivitas Anda. Kualitas tanpa kompromi, harga yang jujur.
        </p>
    </div>
</div>

<!-- Header Filter Bar -->
<div class="bg-white/70 backdrop-blur-xl border-y sticky top-[72px] z-40 transition-all" id="filter-bar">
    <div class="container mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-6">
            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-ul text-primary"></i> Katalog Produk
            </h2>
            <div class="hidden md:flex gap-4">
                <a href="{{ route('storefront.catalog') }}" class="text-sm font-bold {{ !request('category') ? 'text-primary' : 'text-slate-400 hover:text-slate-900' }}">Semua</a>
                @foreach($categories->take(5) as $cat)
                    <a href="{{ route('storefront.catalog', ['category' => $cat->slug]) }}" class="text-sm font-bold {{ request('category') == $cat->slug ? 'text-primary' : 'text-slate-400 hover:text-slate-900' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <form action="{{ route('storefront.catalog') }}" class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." 
                       class="pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 w-40 md:w-64 transition-all focus:w-80 outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-300 group-focus-within:text-primary"></i>
            </form>
            <div class="h-8 w-[1px] bg-slate-100 mx-2"></div>
            <span class="text-xs font-bold text-slate-400">Total: <span class="text-slate-900">{{ $products->total() }}</span></span>
        </div>
    </div>
</div>

<div class="container mx-auto px-6 py-16">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Refined Sidebar -->
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="space-y-10">
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b pb-4">Categories</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('storefront.catalog') }}" class="flex items-center justify-between group">
                                <span class="text-sm {{ !request('category') ? 'text-slate-900 font-black' : 'text-slate-500 font-bold' }} group-hover:text-primary">All Works</span>
                                <span class="text-[10px] bg-slate-50 px-2 py-0.5 rounded-full text-slate-400">{{ \App\Models\EcProduct::count() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('storefront.catalog', ['category' => $category->slug]) }}" class="flex items-center justify-between group">
                                <span class="text-sm {{ request('category') == $category->slug ? 'text-slate-900 font-black' : 'text-slate-500 font-bold' }} group-hover:text-primary">{{ $category->name }}</span>
                                <span class="text-[10px] bg-slate-50 px-2 py-0.5 rounded-full text-slate-400">{{ $category->products_count ?? $category->products()->count() }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-slate-900 rounded-[32px] p-8 text-white relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <i class="fa-solid fa-gem text-4xl mb-6 text-indigo-400"></i>
                    <h3 class="text-xl font-black mb-4 leading-tight">Member Eksklusif</h3>
                    <p class="text-xs text-slate-400 mb-8 leading-relaxed">Dapatkan akses awal ke koleksi terbatas dan penawaran khusus member.</p>
                    <button class="w-full py-4 bg-white text-slate-900 rounded-2xl text-xs font-black shadow-xl hover:scale-105 transition-transform">Gabung Sekarang</button>
                    <i class="fa-solid fa-crown absolute -bottom-6 -right-6 text-8xl text-white/5 rotate-12"></i>
                </div>
            </div>
        </div>

        <!-- Product Grid Premium -->
        <div class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-10">
                @forelse($products as $product)
                <div class="group relative flex flex-col h-full bg-white rounded-[32px] border border-slate-100/60 overflow-hidden hover:border-primary/20 transition-all duration-500 hover:shadow-[0_32px_80px_rgba(0,0,0,0.06)]" data-aos="fade-up">
                    <!-- Image Wrapper -->
                    <a href="{{ route('storefront.product', $product->slug) }}" class="relative block bg-[#F5F5F7] aspect-[4/5] overflow-hidden">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-200">
                                <i class="fa-solid fa-layer-group text-7xl"></i>
                            </div>
                        @endif
                        
                        <!-- Floating Badges -->
                        <div class="absolute top-6 left-6 flex flex-col gap-2">
                            @if($product->price < $product->compare_at_price)
                                <span class="bg-indigo-600 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg">PREMIUM DEAL</span>
                            @endif
                        </div>

                        <!-- Action Overlay -->
                        <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-6 inset-x-6 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 flex justify-center">
                            @if($product->stock > 0)
                            <button onclick="event.preventDefault(); addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})" class="bg-white text-slate-900 py-3 px-8 rounded-2xl text-xs font-black shadow-2xl hover:bg-slate-900 hover:text-white transition-all transform active:scale-95">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Keranjang
                            </button>
                            @else
                            <div class="bg-slate-200 text-slate-400 py-3 px-8 rounded-2xl text-xs font-black cursor-not-allowed">Habis Stok</div>
                            @endif
                        </div>
                    </a>

                    <!-- Detail Info -->
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $product->category->name }}</span>
                            <div class="flex gap-0.5 text-xs text-yellow-400">
                                <i class="fa-solid fa-star"></i>
                                <span class="text-[10px] text-slate-400 font-bold ml-1">4.9</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 leading-tight group-hover:text-primary transition-colors">
                            <a href="{{ route('storefront.product', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                            <div class="flex flex-col">
                                @if($product->price < $product->compare_at_price)
                                    <span class="text-xs text-slate-300 line-through font-bold">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</span>
                                @endif
                                <span class="text-lg font-black text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-primary group-hover:text-white transition-all">
                                <i class="fa-solid fa-arrow-right-long"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- High-End Empty State -->
                <div class="col-span-full py-24 px-6 flex flex-col items-center text-center">
                    <div class="w-32 h-32 bg-slate-50 rounded-[40px] flex items-center justify-center text-slate-200 text-6xl mb-8">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Ruang Kosong</h3>
                    <p class="text-slate-500 max-w-sm font-medium leading-relaxed">Kami sedang menyiapkan kurasi produk terbaru untuk kategori ini. Kembali lagi segera!</p>
                    <a href="{{ route('storefront.catalog') }}" class="mt-10 px-8 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black hover:bg-slate-800 transition-all">Explore All Product</a>
                </div>
                @endforelse
            </div>

            <!-- Custom Pagination -->
            <div class="mt-24 flex justify-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
