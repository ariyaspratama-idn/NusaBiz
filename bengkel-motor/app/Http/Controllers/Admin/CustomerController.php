<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    protected BarcodeService $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->with('vehicles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('membership_barcode', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
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

        $customer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Auto-generate barcode
        $this->barcodeService->assignBarcodeToCustomer($customer);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan dengan barcode: ' . $customer->membership_barcode);
    }

    public function show(User $customer)
    {
        $customer->load(['vehicles.serviceHistories', 'bookings.workOrder']);
        
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $customer->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil diupdate');
    }

    public function destroy(User $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus');
    }

    public function printBarcode(User $customer)
    {
        if (!$customer->membership_barcode) {
            $this->barcodeService->assignBarcodeToCustomer($customer);
        }

        $barcodeImage = $this->barcodeService->generateBarcodeImage($customer->membership_barcode);
        
        return view('admin.customers.barcode', compact('customer', 'barcodeImage'));
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $customer = $this->barcodeService->lookupByBarcode($request->barcode);

        if (!$customer) {
            return redirect()->back()->with('error', 'Customer dengan barcode tersebut tidak ditemukan');
        }

        return redirect()->route('admin.customers.show', $customer);
    }
}
