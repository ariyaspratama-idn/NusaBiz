<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:branches,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:PUSAT,CABANG',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'code' => 'required|unique:branches,code,' . $branch->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:PUSAT,CABANG',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }
}
