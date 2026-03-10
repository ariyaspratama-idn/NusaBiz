@extends('layouts.storefront')

@section('title', 'Portal Berita NusaBiz')
@section('meta_description', 'Portal berita, informasi, edukasi bisnis, dan artikel terbaru dari NusaBiz by Wave Project.')

@section('content')
<!-- Breaking News / Headline Bar -->
<div class="bg-gray-900 text-white font-medium text-sm py-2 px-4 shadow-md sticky top-[64px] z-40 flex items-center gap-4 overflow-hidden border-b border-gray-800">
    <div class="bg-red-600 text-white px-3 py-1 rounded font-bold uppercase tracking-wider whitespace-nowrap animate-pulse flex-shrink-0">
        <i class="fa-solid fa-bolt mr-1"></i> Terhangat
    </div>
    <div class="marquee-container flex-1 overflow-hidden">
        <div class="marquee-content whitespace-nowrap animate-[marquee_20s_linear_infinite] hover:pause">
            @foreach($articles->take(3) as $headline)
                <a href="{{ route('storefront.article', $headline->slug) }}" class="inline-block mx-4 hover:text-yellow-400 transition-colors">
                    <span class="text-gray-400 mr-2">{{ $headline->published_at ? $headline->published_at->format('H:i') : '' }}</span>
                    {{ $headline->title }}
                </a>
                <span class="text-gray-600">|</span>
            @endforeach
        </div>
    </div>
</div>

<style>
    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .hover\:pause:hover { animation-play-state: paused; }
</style>

<div class="container mx-auto px-4 py-8 md:py-10">
    
    <!-- Title Section & Search Form -->
    <div class="flex flex-col md:flex-row md:items-center justify-between border-b-4 border-gray-900 pb-4 mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 uppercase tracking-tight">Kabar <span class="text-primary">NusaBiz</span></h1>
            <div class="text-gray-500 font-medium text-sm mt-1">
                <i class="fa-regular fa-calendar mr-2"></i>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
        
        <!-- Search Box -->
        <div class="w-full md:w-80">
            <form action="{{ route('storefront.articles') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita atau artikel..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block pl-4 pr-10 py-2.5 transition-colors">
                <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Layout: Headline + Sidebar -->
    @if($articles->count() > 0 && !request('search'))
    <div class="flex flex-col lg:flex-row gap-8 mb-16">
        
        <!-- Main Headline (Left) -->
        @php $mainArticle = $articles->first(); @endphp
        <div class="w-full lg:w-2/3 group cursor-pointer relative rounded-xl overflow-hidden shadow-lg h-[400px] md:h-[500px]">
            <a href="{{ route('storefront.article', $mainArticle->slug) }}" class="block w-full h-full">
                @if($mainArticle->image_path)
                    <img src="{{ Storage::url($mainArticle->image_path) }}" alt="{{ $mainArticle->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                @else
                    <div class="w-full h-full bg-indigo-900 flex items-center justify-center">
                        <i class="fa-solid fa-newspaper text-8xl text-white/20"></i>
                    </div>
                @endif
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent opacity-90 group-hover:opacity-100 transition-opacity"></div>
                
                <!-- Content -->
                <div class="absolute bottom-0 inset-x-0 p-6 md:p-10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-primary text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded shadow-md">Top Story</span>
                        <span class="text-gray-300 text-sm"><i class="fa-regular fa-clock mr-1"></i> {{ $mainArticle->published_at ? $mainArticle->published_at->diffForHumans() : $mainArticle->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-4 line-clamp-3 leading-tight group-hover:text-yellow-400 transition-colors drop-shadow-md">
                        {{ $mainArticle->title }}
                    </h2>
                    
                    <p class="text-gray-300 text-base md:text-lg line-clamp-2 md:line-clamp-3 mb-6 max-w-3xl drop-shadow">
                        {{ Str::limit(strip_tags($mainArticle->content), 200) }}
                    </p>
                    
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold text-sm border border-white/30">
                            {{ substr($mainArticle->author->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="font-medium text-sm">{{ $mainArticle->author->name ?? 'Redaksi NusaBiz' }}</span>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Trending Sidebar (Right) -->
        <div class="w-full lg:w-1/3 flex flex-col gap-6">
            <h3 class="text-xl font-black text-gray-900 uppercase border-l-4 border-primary pl-3 flex items-center gap-2">
                <i class="fa-solid fa-fire text-red-500"></i> Sedang Hangat
            </h3>
            
            <div class="flex flex-col gap-5">
                @foreach($articles->skip(1)->take(4) as $index => $article)
                <a href="{{ route('storefront.article', $article->slug) }}" class="flex gap-4 group">
                    <div class="text-4xl font-black text-gray-200 group-hover:text-primary transition-colors opacity-80 mt-1">
                        0{{ $index + 1 }}
                    </div>
                    <div class="flex-1 border-b border-gray-100 pb-4">
                        <div class="text-xs font-bold text-primary uppercase tracking-wider mb-1">Berita Pilihan</div>
                        <h4 class="text-base font-bold text-gray-800 line-clamp-2 leading-snug group-hover:text-primary transition-colors mb-2">
                            {{ $article->title }}
                        </h4>
                        <div class="flex items-center gap-4 text-xs text-gray-500 font-medium">
                            <span><i class="fa-solid fa-pen-nib mr-1 text-gray-400"></i> {{ explode(' ', trim($article->author->name ?? 'Redaksi Keuangan'))[0] }}</span>
                            <span><i class="fa-regular fa-clock mr-1 text-gray-400"></i> {{ $article->published_at ? $article->published_at->format('d M') : '' }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Ad Placement Placeholder -->
            <div class="mt-auto bg-gray-100 rounded-xl border border-gray-200 overflow-hidden relative group">
                <div class="absolute inset-0 bg-primary/5 flex items-center justify-center z-0">
                    <span class="text-gray-400 font-bold tracking-widest uppercase text-sm">Space Iklan Mitra</span>
                </div>
                <div class="relative z-10 p-6 text-center">
                    <h4 class="font-black text-xl text-gray-800 mb-2">NusaBiz App</h4>
                    <p class="text-sm text-gray-600 mb-4">Solusi pintar untuk kelola inventori dan keuangan bisnis Anda di satu genggaman.</p>
                    <button class="bg-gray-900 text-white px-4 py-2 rounded font-bold text-sm w-full hover:bg-black transition-colors">Pelajari Lebih Lanjut</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Section: Berita Terbaru Grid -->
    <div class="mt-12">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-black text-gray-900 border-b-2 border-gray-200 pb-3 flex items-center gap-3 uppercase flex-1">
                @if(request('search'))
                    Hasil Pencarian: "{{ request('search') }}"
                @else
                    Sorotan Terbaru
                @endif
                <span class="h-1 bg-gray-100 rounded-full flex-1 ml-4 block hidden sm:block"></span>
            </h3>
            
            @if(request('search'))
                <a href="{{ route('storefront.articles') }}" class="text-sm font-bold text-red-500 hover:text-red-700 transition-colors ml-4 flex-shrink-0">
                    <i class="fa-solid fa-xmark mr-1"></i> Reset Pencarian
                </a>
            @endif
        </div>
        
        @if($articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @php 
                $gridArticles = request('search') ? $articles : $articles->skip(5); 
            @endphp
            
            @foreach($gridArticles as $article)
            <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all group flex flex-col h-full hover:-translate-y-1">
                <a href="{{ route('storefront.article', $article->slug) }}" class="block relative h-48 overflow-hidden bg-gray-100">
                    @if($article->image_path)
                        <img src="{{ Storage::url($article->image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-800">
                            <i class="fa-solid fa-image text-4xl text-gray-600"></i>
                        </div>
                    @endif
                    
                    <div class="absolute bottom-0 left-0 bg-primary text-white px-3 py-1 font-bold text-xs">
                        {{ $article->published_at ? $article->published_at->format('d M') : date('d M') }}
                    </div>
                </a>
                
                <div class="p-5 flex flex-col flex-1">
                    <a href="{{ route('storefront.article', $article->slug) }}">
                        <h2 class="text-lg font-bold text-gray-900 mb-3 line-clamp-3 group-hover:text-primary transition-colors leading-snug">
                            {{ $article->title }}
                        </h2>
                    </a>
                    
                    <p class="text-gray-600 text-sm mb-5 line-clamp-2 mt-auto">
                        {{ Str::limit(strip_tags($article->content), 80) }}
                    </p>
                    
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-semibold text-gray-500">
                        <span class="hover:text-primary cursor-pointer"><i class="fa-regular fa-comment mr-1"></i> Tulis Komentar</span>
                        <div class="flex gap-2">
                            <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 cursor-pointer"><i class="fa-solid fa-share-nodes"></i></span>
                            <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 cursor-pointer"><i class="fa-regular fa-bookmark"></i></span>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <!-- Modern Empty State -->
        <div class="w-full bg-white rounded-3xl border-2 border-dashed border-gray-200 p-12 flex flex-col items-center justify-center text-center shadow-sm">
            <div class="relative w-32 h-32 mb-6">
                <div class="absolute inset-0 bg-primary/10 rounded-full animate-ping opacity-75"></div>
                <div class="relative w-full h-full bg-gradient-to-tr from-gray-100 to-gray-50 rounded-full flex items-center justify-center text-gray-400 text-6xl shadow-inner border border-white">
                    <i class="fa-regular fa-newspaper"></i>
                </div>
                <div class="absolute -right-2 -bottom-2 w-12 h-12 bg-white rounded-full flex items-center justify-center text-red-500 text-2xl shadow-md border border-gray-100">
                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                </div>
            </div>
            
            <h3 class="text-2xl font-black text-gray-800 mb-3">
                @if(request('search'))
                    Tidak Menemukan Berita "{{ request('search') }}"
                @else
                    Belum Ada Kabar Terbaru
                @endif
            </h3>
            
            <p class="text-gray-500 max-w-md mx-auto mb-8 text-lg">
                @if(request('search'))
                    Kami tidak dapat menemukan berita atau artikel yang cocok dengan kata kunci tersebut. Coba gunakan kata kunci lain.
                @else
                    Tim redaksi kami sedang menyiapkan konten-konten dan artikel menarik yang akan segera diterbitkan untuk Anda!
                @endif
            </p>
            
            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                    Kembali ke Beranda
                </a>
                @if(request('search'))
                <a href="{{ route('storefront.articles') }}" class="px-6 py-3 bg-primary text-white font-bold rounded-xl shadow-md shadow-primary/30 hover:bg-primary-dark transition-colors">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Reset Pencarian
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
