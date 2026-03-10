@extends('layouts.storefront')

@section('title', $product->name . ' — Official Store')
@section('meta_description', Str::limit(strip_tags($product->description), 150))

@section('content')
<div class="container mx-auto px-6 py-12">
    <!-- Breadcrumbs Refined -->
    <div class="flex items-center text-[10px] font-black uppercase tracking-[2px] text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-3 opacity-30">/</span>
        <a href="{{ route('storefront.catalog') }}" class="hover:text-primary transition-colors">Archive</a>
        <span class="mx-3 opacity-30">/</span>
        <span class="text-slate-900">{{ $product->name }}</span>
    </div>

    <div class="bg-white rounded-[40px] border border-slate-100/60 p-8 md:p-16 shadow-[0_40px_100px_rgba(0,0,0,0.04)] mb-20">
        <div class="flex flex-col lg:flex-row gap-20">
            
            <!-- Product Showcase -->
            <div class="w-full lg:w-1/2">
                <div class="sticky top-[120px]">
                    <div class="relative rounded-[32px] overflow-hidden bg-[#F5F5F7] aspect-square mb-8 group">
                        @if($product->image_path)
                            <img id="mainImage" src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-contain mix-blend-multiply transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-200">
                                <i class="fa-solid fa-image text-8xl"></i>
                            </div>
                        @endif
                        
                        @if($product->price < $product->compare_at_price)
                            <div class="absolute top-8 right-8 bg-slate-900 text-white px-5 py-2 rounded-2xl text-xs font-black shadow-xl">
                                -{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}% OFF
                            </div>
                        @endif
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-slate-50 p-6 rounded-2xl text-center">
                            <i class="fa-solid fa-shield-check text-xl text-primary mb-2"></i>
                            <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Original</span>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl text-center">
                            <i class="fa-solid fa-truck-fast text-xl text-primary mb-2"></i>
                            <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Secure</span>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl text-center">
                            <i class="fa-solid fa-rotate-left text-xl text-primary mb-2"></i>
                            <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">7 Days</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Narrative -->
            <div class="w-full lg:w-1/2 flex flex-col">
                <div class="mb-10 pb-10 border-b border-slate-50">
                    <div class="text-xs font-black text-indigo-500 mb-4 tracking-[3px] uppercase">{{ $product->category->name }}</div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 leading-[1.1] tracking-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-6 mb-8">
                        <div class="flex items-center gap-1.5 text-yellow-400">
                            <i class="fa-solid fa-star text-sm"></i>
                            <span class="text-sm font-black text-slate-900">4.9</span>
                            <span class="text-xs text-slate-400 font-bold">(120 Reviews)</span>
                        </div>
                        <div class="h-4 w-[1px] bg-slate-100"></div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">In Stock</span>
                        </div>
                    </div>
                    
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl font-black text-slate-900" id="productPrice">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @if($product->price < $product->compare_at_price)
                            <span class="text-xl font-bold text-slate-300 line-through">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="prose prose-slate max-w-none text-slate-500 leading-relaxed font-medium mb-12">
                    {!! nl2br(e($product->description)) !!}
                </div>
                
                <!-- Variant Selection Premium -->
                @if($product->variants && $product->variants->count() > 0)
                <div class="mb-12">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Select Configuration</h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach($product->variants as $variant)
                            <label class="cursor-pointer group">
                                <input type="radio" name="variant" class="peer sr-only" value="{{ $variant->id }}" 
                                    data-price="{{ $variant->price }}" 
                                    data-stock="{{ $variant->stock }}"
                                    {{ $loop->first ? 'checked' : '' }}
                                    {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                <div class="px-6 py-4 rounded-2xl border-2 border-slate-100 font-black text-sm peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all group-hover:border-slate-200 peer-disabled:opacity-30">
                                    {{ $variant->name }}
                                    <div class="text-[10px] opacity-40 mt-1 font-bold">Standard Edition</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Action Flow -->
                <div class="mt-auto grid grid-cols-1 sm:grid-cols-4 gap-4 pb-8">
                    <div class="sm:col-span-1 flex items-center bg-slate-50 rounded-2xl p-2 h-16">
                        <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900" onclick="document.getElementById('qty').value = Math.max(1, parseInt(document.getElementById('qty').value) - 1)"><i class="fa-solid fa-minus"></i></button>
                        <input type="number" id="qty" value="1" min="1" max="{{ $product->stock }}" class="flex-1 bg-transparent border-none text-center font-black text-slate-900 focus:ring-0 p-0">
                        <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900" onclick="document.getElementById('qty').value = Math.min({{ $product->stock }}, parseInt(document.getElementById('qty').value) + 1)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    
                    <button class="sm:col-span-1 h-16 bg-white border-2 border-slate-900 text-slate-900 font-black rounded-2xl hover:bg-slate-900 hover:text-white transition-all transform active:scale-95 flex items-center justify-center"
                        onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, document.getElementById('qty').value)">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </button>
                    
                    <button class="sm:col-span-2 h-16 bg-slate-900 text-white font-black rounded-2xl shadow-2xl hover:bg-slate-800 transition-all transform active:scale-95 flex items-center justify-center gap-3"
                        onclick="addToCartAndCheckout({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, document.getElementById('qty').value)">
                        Purchase Now <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Treasures -->
    @if($related->count() > 0)
    <div class="mt-32">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">You might also like</h2>
            <a href="{{ route('storefront.catalog') }}" class="text-xs font-black text-primary uppercase tracking-widest hover:translate-x-2 transition-transform inline-flex items-center gap-3">
                View Archive <i class="fa-solid fa-arrow-right-long"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            @foreach($related as $item)
            <div class="group">
                <a href="{{ route('storefront.product', $item->slug) }}" class="block relative aspect-square bg-[#F5F5F7] rounded-[32px] overflow-hidden mb-6">
                    @if($item->image_path)
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @endif
                </a>
                <h3 class="font-bold text-slate-900 mb-2 leading-tight group-hover:text-primary transition-colors">
                    <a href="{{ route('storefront.product', $item->slug) }}">{{ $item->name }}</a>
                </h3>
                <div class="text-xs font-black text-slate-400">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('input[name="variant"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const price = this.dataset.price;
            const stock = this.dataset.stock;
            document.getElementById('productPrice').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            document.getElementById('qty').max = stock;
        });
    });
</script>
@endsection
