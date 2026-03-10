<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Jika Super Admin, bisa lihat semua. Jika bukan, kunci ke branch sendiri.
        if ($user && $user->role !== User::ROLE_SUPER_ADMIN && $user->branch_id) {
            $branches = Branch::where('id', $user->branch_id)->where('is_active', true)->get();
        } else {
            $branches = Branch::where('is_active', true)->get();
        }

        $accounts = Account::where('is_header', false)->get();
        $contacts = \App\Models\Contact::all(); // Provide all contacts for debt feature

        $activeRegister = null;
        if ($user) {
            $activeRegister = \App\Models\CashRegister::where('user_id', $user->id)
                            ->where('status', 'OPEN')
                            ->first();
        }

        $defaultBranch = ($user && $user->branch_id) ? Branch::find($user->branch_id) : $branches->first();
        $defaultAccount = $accounts->where('type', 'CASH')->first() ?? $accounts->first();

        return view('pos.index', compact('branches', 'accounts', 'defaultBranch', 'defaultAccount', 'contacts', 'activeRegister'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:1',
            'branch_id' => 'required|exists:branches,id',
            'account_id' => 'required|exists:accounts,id',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        try {
            // Sederhanakan deskripsi untuk menyimpan semua item
            $itemDetails = collect($request->items)->map(function($item) {
                return "{$item['name']} (x{$item['quantity']})";
            })->implode(', ');
            $description = "POS Multi-Item: " . substr($itemDetails, 0, 200);

            $posService = new \App\Services\PosTransactionService();
            $transaction = $posService->createPosTransaction([
                'branch_id' => $validated['branch_id'],
                'account_id' => $validated['account_id'],
                'total' => $validated['total'],
                'description' => $description,
                'items' => $request->items,
                'payment_status' => $request->input('payment_status', 'PAID'),
                'contact_id' => $request->input('contact_id', null),
                'cash_register_id' => $request->input('cash_register_id', null)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Super POS berhasil disimpan!',
                'data' => [
                    'transaction_no' => $transaction->transaction_no,
                    'total_bayar' => $validated['total'],
                    'item_count' => count($request->items)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Super POS Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
