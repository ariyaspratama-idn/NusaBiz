<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $accounts = Account::where('is_header', false)->get();
        return view('products.create', compact('categories', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
            'purchase_price' => 'nullable|numeric',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $accounts = Account::where('is_header', false)->get();
        return view('products.edit', compact('product', 'categories', 'accounts'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    // BoM / Recipe Methods
    public function recipes(Product $product)
    {
        $allProducts = Product::where('id', '!=', $product->id)->get();
        $product->load('recipes.material');
        return view('products.recipes', compact('product', 'allProducts'));
    }

    public function addRecipe(Request $request, Product $product)
    {
        $request->validate([
            'material_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $product->recipes()->updateOrCreate(
            ['material_id' => $request->material_id],
            ['quantity' => $request->quantity]
        );

        return back()->with('success', 'Resep berhasil diperbarui.');
    }

    public function removeRecipe(Product $product, $recipeId)
    {
        $product->recipes()->where('id', $recipeId)->delete();
        return back()->with('success', 'Bahan berhasil dihapus dari resep.');
    }
}
