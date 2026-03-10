<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EcProduct;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = EcProduct::with('category');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $products = $query->latest()->paginate(15);
        $categories = ProductCategory::all();
        $lowStockCount = EcProduct::whereRaw('stock <= min_stock_alert')->count();
        return view('admin.products.index', compact('products', 'categories', 'lowStockCount'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'nullable|exists:product_categories,id',
            'sku'             => 'nullable|string|unique:ec_products,sku',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'sale_price'      => 'nullable|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:0',
            'weight'          => 'required|numeric|min:0',
            'status'          => 'required|in:active,inactive,out_of_stock',
            'is_featured'     => 'boolean',
            'main_image'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $product = EcProduct::create($validated);

        // Handle additional images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(EcProduct $product)
    {
        $categories = ProductCategory::where('is_active', true)->get();
        $product->load('images', 'variants');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, EcProduct $product)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'nullable|exists:product_categories,id',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'sale_price'      => 'nullable|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:0',
            'weight'          => 'required|numeric|min:0',
            'status'          => 'required|in:active,inactive,out_of_stock',
            'is_featured'     => 'boolean',
            'main_image'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function updateStock(Request $request, EcProduct $product)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        $product->update(['stock' => $request->stock]);
        return response()->json(['success' => true, 'stock' => $product->stock]);
    }

    public function updatePrice(Request $request, EcProduct $product)
    {
        $request->validate([
            'price'      => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
        ]);
        $product->update($request->only('price', 'sale_price'));
        return response()->json(['success' => true]);
    }

    public function destroy(EcProduct $product)
    {
        if ($product->main_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->main_image);
        }
        
        // Also delete additional images
        foreach ($product->images as $image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
