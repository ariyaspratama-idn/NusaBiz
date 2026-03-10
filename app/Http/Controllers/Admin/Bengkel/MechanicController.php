<?php

namespace App\Http\Controllers\Admin\Bengkel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MechanicController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'mechanic')->withCount('workOrders');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $mechanics = $query->latest()->paginate(15);

        return view('admin.mechanics.index', compact('mechanics'));
    }

    public function create()
    {
        return view('admin.mechanics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mechanic',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('admin.mechanics.index')
            ->with('success', 'Mekanik berhasil ditambahkan');
    }

    public function show(User $mechanic)
    {
        $mechanic->load(['workOrders.booking.vehicle']);
        
        return view('admin.mechanics.show', compact('mechanic'));
    }

    public function edit(User $mechanic)
    {
        return view('admin.mechanics.edit', compact('mechanic'));
    }

    public function update(Request $request, User $mechanic)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $mechanic->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $mechanic->update($validated);

        return redirect()->route('admin.mechanics.index')
            ->with('success', 'Mekanik berhasil diupdate');
    }

    public function destroy(User $mechanic)
    {
        $mechanic->delete();

        return redirect()->route('admin.mechanics.index')
            ->with('success', 'Mekanik berhasil dihapus');
    }
}

