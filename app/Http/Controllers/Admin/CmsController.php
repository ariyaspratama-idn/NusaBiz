<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsArticle;
use App\Models\CmsTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    // ========== ARTICLES ==========
    public function articles()
    {
        $articles = CmsArticle::with('author')->latest()->paginate(15);
        return view('admin.cms.articles.index', compact('articles'));
    }

    public function articleCreate()
    {
        return view('admin.cms.articles.create');
    }

    public function articleStore(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'body'             => 'required|string',
            'excerpt'          => 'nullable|string|max:300',
            'category'         => 'required|in:berita,artikel,promo',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'featured_image'   => 'nullable|image|max:22048',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        $validated['author_id'] = auth()->id();
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;

        CmsArticle::create($validated);
        return redirect()->route('admin.cms.articles')->with('success', 'Artikel berhasil dipublikasikan!');
    }

    public function articleEdit(CmsArticle $article)
    {
        return view('admin.cms.articles.edit', compact('article'));
    }

    public function articleUpdate(Request $request, CmsArticle $article)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'body'             => 'required|string',
            'excerpt'          => 'nullable|string|max:300',
            'category'         => 'required|in:berita,artikel,promo',
            'status'           => 'required|in:draft,published',
            'featured_image'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($article->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        if ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);
        return back()->with('success', 'Artikel berhasil diperbarui!');
    }

    public function articleDestroy(CmsArticle $article)
    {
        if ($article->featured_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($article->featured_image);
        }
        $article->delete();
        return redirect()->route('admin.cms.articles')->with('success', 'Artikel berhasil dihapus!');
    }

    // ========== TESTIMONIALS ==========
    public function testimonials()
    {
        $testimonials = CmsTestimonial::latest()->paginate(10);
        return view('admin.cms.testimonials', compact('testimonials'));
    }

    public function testimonialStore(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:100',
            'customer_position' => 'nullable|string|max:100',
            'content'           => 'required|string',
            'rating'            => 'required|integer|between:1,5',
            'customer_avatar'   => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('customer_avatar')) {
            $validated['customer_avatar'] = $request->file('customer_avatar')->store('testimonials', 'public');
        }

        CmsTestimonial::create($validated);
        return back()->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function testimonialDestroy(CmsTestimonial $testimonial)
    {
        if ($testimonial->customer_avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($testimonial->customer_avatar);
        }
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus!');
    }

    // ========== WEB SETTINGS ==========
    public function settings()
    {
        $settings = [
            'company_history' => \App\Models\Setting::get('company_history', 'Berawal dari visi sederhana...'),
            'company_vision'  => \App\Models\Setting::get('company_vision', 'Menjadi platform ekosistem bisnis digital terpadu...'),
            'company_mission' => \App\Models\Setting::get('company_mission', "Menyediakan kualitas produk unggulan.\nMeningkatkan pengalaman berbelanja.\nMemberikan pelayanan CS responsif."),
            'company_motto'   => \App\Models\Setting::get('company_motto', 'Gaya Hidup Masa Depan, Dimulai Hari Ini.'),
            'terms_conditions' => \App\Models\Setting::get('terms_conditions', 'Selamat datang di NusaBiz...'),
            'privacy_policy'  => \App\Models\Setting::get('privacy_policy', 'Kami mengumpulkan Informasi Pribadi...'),
        ];

        return view('admin.cms.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'company_history' => 'nullable|string',
            'company_vision'  => 'nullable|string',
            'company_mission' => 'nullable|string',
            'company_motto'   => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'privacy_policy'  => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            \App\Models\Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}
