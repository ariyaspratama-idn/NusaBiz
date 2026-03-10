<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    public function index(Request $request)
    {
        $query = SparePart::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('low_stock')) {
            $query->whereRaw('stock <= min_stock');
        }

        $spareParts = $query->latest()->paginate(15);
        $categories = SparePart::distinct()->pluck('category');

        return view('admin.spare-parts.index', compact('spareParts', 'categories'));
    }

    public function create()
    {
        return view('admin.spare-parts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'sku' => ['required', 'string', 'max:50', 'unique:spare_parts'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:100'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        SparePart::create($validated);

        return redirect()->route('admin.spare-parts.index')
            ->with('success', 'Spare part berhasil ditambahkan');
    }

    public function show(SparePart $sparePart)
    {
        $sparePart->load('workOrderItems.workOrder');
        
        return view('admin.spare-parts.show', compact('sparePart'));
    }

    public function edit(SparePart $sparePart)
    {
        return view('admin.spare-parts.edit', compact('sparePart'));
    }

    public function update(Request $request, SparePart $sparePart)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'sku' => ['required', 'string', 'max:50', 'unique:spare_parts,sku,' . $sparePart->id],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:100'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $sparePart->update($validated);

        return redirect()->route('admin.spare-parts.index')
            ->with('success', 'Spare part berhasil diupdate');
    }

    public function destroy(SparePart $sparePart)
    {
        $sparePart->delete();

        return redirect()->route('admin.spare-parts.index')
            ->with('success', 'Spare part berhasil dihapus');
    }

    public function adjustStock(Request $request, SparePart $sparePart)
    {
        $request->validate([
            'adjustment' => ['required', 'integer'],
            'note' => ['nullable', 'string'],
        ]);

        $newStock = $sparePart->stock + $request->adjustment;

        if ($newStock < 0) {
            return back()->with('error', 'Stock tidak boleh negatif');
        }

        $sparePart->update(['stock' => $newStock]);

        return back()->with('success', 'Stock berhasil disesuaikan');
    }
}
