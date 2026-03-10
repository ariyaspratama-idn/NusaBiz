@extends('layouts.storefront')

@section('title', 'NusaBiz — Solusi Bisnis Terpadu Masa Depan')
@section('meta_description', 'Platform bisnis terpadu untuk UMKM Indonesia. Menyatukan teknologi dan strategi untuk pertumbuhan eksponensial.')

@section('content')
<!-- Premium Hero Home -->
<div class="hero">
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>
    
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fa-solid fa-bolt-lightning mr-2"></i> Next-Gen Business Suite
        </div>
        <h1>Elevate Your <br><span>Digital Presence</span></h1>
        <p>Solusi bisnis terpadu yang menyatukan profil perusahaan profesional dan ekosistem e-commerce dalam satu platform yang elegant dan powerful.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('storefront.catalog') }}" class="btn-premium-primary">
                Explore Collection <i class="fa-solid fa-arrow-right-long"></i>
            </a>
            <a href="#profil-berita" class="btn-premium-outline">
                Learn Our Story
            </a>
        </div>
    </div>
</div>

<!-- Category Carousel / List Premium -->
<div class="container mx-auto px-6 py-20">
    <div class="flex items-end justify-between mb-12">
        <div>
            <span class="section-label">Categories</span>
            <h2 class="text-4xl font-black text-slate-900 tracking-tight">Browse by Collection</h2>
        </div>
        <a href="{{ route('storefront.catalog') }}" class="text-sm font-black text-primary hover:translate-x-2 transition-transform inline-flex items-center gap-2">
            View All <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories->take(4) as $cat)
        <a href="{{ route('storefront.catalog', ['category' => $cat->slug]) }}" class="group relative aspect-[4/3] rounded-[32px] overflow-hidden bg-slate-50 border border-slate-100 hover:border-primary/20 transition-all">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="h-full flex flex-col items-center justify-center p-8 text-center transition-transform group-hover:scale-105">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary text-2xl mb-4 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <h3 class="font-black text-slate-900 group-hover:text-white transition-colors">{{ $cat->name }}</h3>
                <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest group-hover:text-white/60">{{ $cat->products_count }} items</span>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- About & News Split -->
<div id="profil-berita" class="bg-slate-50/50 py-32 border-y border-slate-100/60">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-20">
            <!-- Left: News -->
            <div class="lg:col-span-7">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <span class="section-label">Journal</span>
                        <h2 class="text-4xl font-black text-slate-900 tracking-tight">The Latest Stories</h2>
                    </div>
                </div>

                <div class="space-y-8">
                    @foreach($latestArticles as $article)
                    <a href="{{ route('storefront.article', $article->slug) }}" class="flex items-center gap-8 group">
                        <div class="w-32 h-32 md:w-48 md:h-48 rounded-[24px] overflow-hidden flex-shrink-0 bg-slate-100">
                            @if($article->featured_image ?? $article->image_path)
                                <img src="{{ Storage::url($article->featured_image ?? $article->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-200 text-3xl"><i class="fa-solid fa-newspaper"></i></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <span class="text-[10px] font-black text-primary uppercase tracking-[2px] mb-2 block">{{ $article->published_at ? $article->published_at->format('M d, Y') : $article->created_at->format('M d, Y') }}</span>
                            <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-4 line-clamp-2 leading-tight group-hover:text-primary transition-colors">{{ $article->title }}</h3>
                            <p class="text-sm text-slate-500 font-medium line-clamp-2 hidden md:block">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Right: About Short -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-[40px] p-12 shadow-xl shadow-slate-900/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>
                    <span class="section-label">Our Philosophy</span>
                    <h3 class="text-3xl font-black text-slate-900 mb-8 tracking-tight">Crafting the Future of Indonesian UMKM</h3>
                    
                    <div class="space-y-8 mb-12">
                        <div>
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">History</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ Str::limit($settings['history'], 150) }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-slate-50 p-6 rounded-2xl">
                                <h4 class="text-xs font-black text-primary uppercase tracking-widest mb-2">Vision</h4>
                                <p class="text-[10px] text-slate-400 font-bold leading-relaxed">{{ Str::limit($settings['vision'], 60) }}</p>
                            </div>
                            <div class="bg-slate-50 p-6 rounded-2xl text-center flex flex-col items-center justify-center">
                                <i class="fa-solid fa-crown text-primary text-xl mb-2"></i>
                                <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Premium Quality</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-900 rounded-[24px] text-white text-center">
                        <i class="fa-solid fa-quote-left text-3xl text-white/10 mb-4"></i>
                        <p class="text-lg font-black italic tracking-wide">"{{ $settings['motto'] }}"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Spotlight Home -->
<div class="container mx-auto px-6 py-32">
    <div class="text-center max-w-2xl mx-auto mb-20">
        <span class="section-label">Featured Spotlight</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">Masterpieces in Motion</h2>
        <p class="text-slate-500 font-medium leading-relaxed">Koleksi pilihan yang menggabungkan estetika modern dengan fungsionalitas tanpa batas.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
        @foreach($featuredProducts->take(4) as $product)
        <div class="group">
            <a href="{{ route('storefront.product', $product->slug) }}" class="block relative aspect-[4/5] bg-slate-50 rounded-[32px] overflow-hidden mb-8">
                @if($product->image_path)
                    <img src="{{ Storage::url($product->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-200 text-6xl"><i class="fa-solid fa-cube"></i></div>
                @endif
                <div class="absolute bottom-6 inset-x-6 opacity-0 group-hover:opacity-100 transition-opacity flex justify-center translate-y-2 group-hover:translate-y-0 transition-all duration-500">
                    <button onclick="event.preventDefault(); addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})" class="bg-white text-slate-900 py-3 px-8 rounded-2xl text-xs font-black shadow-2xl">
                        Add to Cart
                    </button>
                </div>
            </a>
            <div class="flex items-center justify-between px-2">
                <div>
                    <h3 class="font-bold text-slate-900 group-hover:text-primary transition-colors"><a href="{{ route('storefront.product', $product->slug) }}">{{ $product->name }}</a></h3>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $product->category->name }}</span>
                </div>
                <span class="font-black text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-20 text-center">
        <a href="{{ route('storefront.catalog') }}" class="btn-premium-outline inline-flex mx-auto">
            Discover Full Catalog <i class="fa-solid fa-arrow-right-long ml-3"></i>
        </a>
    </div>
</div>

<!-- CTA Home Premium -->
<div class="container mx-auto px-6 mb-20">
    <div class="bg-primary rounded-[48px] p-12 md:p-24 text-white text-center relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-6xl font-black mb-8 leading-none tracking-tight">Ready to Elevate <br>Your Business?</h2>
            <p class="text-indigo-100 text-lg md:text-xl font-medium mb-12 opacity-80 leading-relaxed">Bergabunglah bersama ratusan klien yang telah mempercayakan suplai dan kemitraan bisnis mereka bersama NusaBiz.</p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <button onclick="toggleChat()" class="h-20 px-12 bg-white text-primary font-black rounded-3xl shadow-2xl hover:bg-slate-50 transition-all flex items-center justify-center gap-4 group">
                    <i class="fa-solid fa-comments text-2xl group-hover:animate-bounce"></i> Start Conversation
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
