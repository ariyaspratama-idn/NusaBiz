@extends('layouts.storefront')

@section('title', $article->title)
@section('meta_description', Str::limit(strip_tags($article->content), 150))

@section('content')
<!-- Page Header / Hero Image -->
<div class="relative w-full h-64 md:h-96 bg-gray-900 overflow-hidden">
    @if($article->image_path)
        <img src="{{ Storage::url($article->image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover opacity-60">
    @else
        <div class="absolute inset-0 bg-primary opacity-80 decoration-pattern"></div>
    @endif
    
    <div class="absolute inset-0 flex flex-col justify-end">
        <div class="container mx-auto px-4 pb-12">
            <span class="inline-block bg-white text-primary px-3 py-1 rounded-full text-xs font-bold mb-4 uppercase tracking-wider shadow-sm">
                Berita Bisnis
            </span>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight max-w-4xl drop-shadow-lg">
                {{ $article->title }}
            </h1>
            <div class="flex flex-wrap items-center gap-6 text-white/90 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center border border-white/30 backdrop-blur-sm">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                    <span class="font-medium">{{ $article->author->name ?? 'Admin NusaBiz' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-calendar-days opacity-70"></i>
                    <span>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : $article->created_at->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-eye opacity-70"></i>
                    <span>{{ number_format(rand(120, 1500)) }} kali dibaca</span> <!-- Simulasi view tracker -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Main Content -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-2xl shadow-sm border p-6 md:p-10 lg:p-12 prose prose-lg prose-indigo max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-a:text-primary hover:prose-a:text-primary-dark">
                {!! $article->content !!}
            </div>
            
            <!-- Tags & Share -->
            <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-t pt-8">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-700 mr-2">Tag:</span>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 text-sm rounded-lg cursor-pointer hover:bg-gray-200">#NusaBiz</span>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 text-sm rounded-lg cursor-pointer hover:bg-gray-200">#Bisnis</span>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 text-sm rounded-lg cursor-pointer hover:bg-gray-200">#ECommerce</span>
                </div>
                
                <div class="flex items-center gap-3">
                    <span class="font-bold text-gray-700">Bagikan:</span>
                    <button class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></button>
                    <button class="w-10 h-10 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center hover:bg-sky-500 hover:text-white transition-colors"><i class="fa-brands fa-twitter"></i></button>
                    <button class="w-10 h-10 rounded-full bg-green-100 text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors"><i class="fa-brands fa-whatsapp"></i></button>
                    <button class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-600 hover:text-white transition-colors"><i class="fa-solid fa-link"></i></button>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Widget -->
        <div class="w-full lg:w-1/3 space-y-8">
            <!-- Author Card -->
            <div class="bg-gray-50 rounded-2xl p-6 border text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-primary text-white flex items-center justify-center text-3xl font-bold mb-4 shadow-md border-4 border-white">
                    {{ substr($article->author->name ?? 'A', 0, 1) }}
                </div>
                <h3 class="font-bold text-xl text-gray-900 mb-1">{{ $article->author->name ?? 'Editorial Team' }}</h3>
                <p class="text-primary text-sm font-semibold mb-4">Content Writer & Business Analyst</p>
                <p class="text-gray-600 text-sm mb-4">Tim ahli kami selalu berusaha menghadirkan informasi yang faktual, relevan, dan membantu perkembangan bisnis Anda di ranah digital.</p>
                <a href="#" class="inline-block border border-primary text-primary px-4 py-2 rounded-lg text-sm hover:bg-primary hover:text-white transition-colors font-medium">Artikel Lainnya</a>
            </div>
            
            <!-- Latest News Widget -->
            <div class="bg-white rounded-2xl p-6 border shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-primary pl-3">Berita Terbaru</h3>
                <div class="flex flex-col gap-4">
                    @forelse($latestArticles as $latest)
                    <a href="{{ route('storefront.article', $latest->slug) }}" class="flex gap-4 group">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                            @if($latest->image_path)
                                <img src="{{ Storage::url($latest->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-image text-gray-300"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col justify-center">
                            <h4 class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight group-hover:text-primary transition-colors mb-2">{{ $latest->title }}</h4>
                            <span class="text-xs text-gray-500"><i class="fa-solid fa-clock mr-1"></i> {{ $latest->published_at ? $latest->published_at->format('d M Y') : '' }}</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-500 italic">Belum ada berita lain.</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Banner Widget -->
            <div class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl p-8 text-white relative overflow-hidden text-center shadow-lg">
                <div class="absolute inset-0 bg-black/10"></div>
                --<div class="relative z-10">
                    <i class="fa-solid fa-store text-4xl mb-4 text-yellow-300"></i>
                    <h3 class="font-extrabold text-xl mb-3">Kembangkan Bisnis Anda</h3>
                    <p class="text-white/80 text-sm mb-6 leading-relaxed">Bergabung dan gunakan platform NusaBiz untuk menjangkau lebih banyak pelanggan dan memudahkan manajemen penjualan perusahaan.</p>
                    <a href="{{ route('storefront.catalog') }}" class="inline-block bg-white text-primary font-bold px-6 py-3 rounded-xl hover:bg-gray-100 transition-colors hover:shadow-lg w-full">Lihat Katalog Kami</a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
