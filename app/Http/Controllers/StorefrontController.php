<?php

namespace App\Http\Controllers;

use App\Models\EcProduct;
use App\Models\ProductCategory;
use App\Models\CmsArticle;
use App\Models\CmsTestimonial;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home()
    {
        $featuredProducts = EcProduct::where('status', 'active')
            ->where('is_featured', true)
            ->with('category')
            ->limit(8)
            ->get();

        $latestProducts = EcProduct::where('status', 'active')
            ->latest()
            ->with('category')
            ->limit(8)
            ->get();

        $categories = ProductCategory::where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->get();

        $testimonials = CmsTestimonial::where('is_active', true)->latest()->limit(6)->get();
        $latestArticles = CmsArticle::where('status', 'published')->latest('published_at')->limit(3)->get();

        $settings = [
            'history' => \App\Models\Setting::get('company_history', 'Berawal dari visi sederhana untuk mendigitalisasi UMKM Indonesia, NusaBiz hadir sebagai mitra strategis bagi pertumbuhan bisnis Anda yang berkelanjutan.'),
            'vision'  => \App\Models\Setting::get('company_vision', 'Menjadi platform ekosistem bisnis digital terpadu nomor satu di Indonesia yang memberdayakan pengusaha lokal melalui teknologi mutakhir.'),
            'mission' => \App\Models\Setting::get('company_mission', "Menyediakan kualitas produk unggulan guna memenuhi standar ekspektasi pasar global.\nMeningkatkan efisiensi pengalaman berbelanja pelanggan melalui sistem otomasi cerdas.\nMemberikan pelayanan dukungan teknis yang responsif dan solutif bagi seluruh mitra bisnis."),
            'motto'   => \App\Models\Setting::get('company_motto', 'Gaya Hidup Masa Depan, Dimulai Hari Ini.'),
        ];

        return view('storefront.home', compact(
            'featuredProducts', 'latestProducts', 'categories', 'testimonials', 'latestArticles', 'settings'
        ));
    }

    public function catalog(Request $request)
    {
        $query = EcProduct::where('status', 'active')->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('sort')) {
            match($request->sort) {
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest'     => $query->latest(),
                default      => $query->latest(),
            };
        }

        $products = $query->paginate(12);
        $categories = ProductCategory::where('is_active', true)->get();

        return view('storefront.catalog', compact('products', 'categories'));
    }

    public function productDetail($slug)
    {
        $product = EcProduct::where('slug', $slug)
            ->where('status', 'active')
            ->with(['images', 'variants', 'category'])
            ->firstOrFail();

        $related = EcProduct::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return view('storefront.product-detail', compact('product', 'related'));
    }

    public function article($slug)
    {
        $article = CmsArticle::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $latestArticles = CmsArticle::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('storefront.article', compact('article', 'latestArticles'));
    }

    public function articles(Request $request)
    {
        $query = CmsArticle::where('status', 'published')->with('author');
        
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }
        
        $articles = $query->latest('published_at')->paginate(9);
        return view('storefront.articles', compact('articles'));
    }

    public function terms()
    {
        $content = \App\Models\Setting::get('terms_conditions', 'Konten Syarat & Ketentuan belum diatur.');
        return view('storefront.terms', compact('content'));
    }

    public function privacy()
    {
        $content = \App\Models\Setting::get('privacy_policy', 'Konten Kebijakan Privasi belum diatur.');
        return view('storefront.privacy', compact('content'));
    }
}
