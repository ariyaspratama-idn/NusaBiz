<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CashRegisterController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeRegister = CashRegister::where('user_id', $user->id)
            ->where('status', 'OPEN')
            ->first();

        $history = CashRegister::where('user_id', $user->id)
            ->where('status', 'CLOSED')
            ->orderBy('closed_at', 'desc')
            ->take(10)
            ->get();

        return view('cash_registers.index', compact('activeRegister', 'history'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_balance' => 'required|numeric|min:0',
            'branch_id' => 'required|exists:branches,id'
        ]);

        CashRegister::create([
            'user_id' => auth()->id(),
            'branch_id' => $request->branch_id,
            'opened_at' => now(),
            'opening_balance' => $request->opening_balance,
            'status' => 'OPEN'
        ]);

        return back()->with('success', 'Shift kasir berhasil dibuka!');
    }

    public function close(Request $request, $id)
    {
        $register = CashRegister::findOrFail($id);
        
        $request->validate([
            'closing_physical_balance' => 'required|numeric|min:0',
        ]);

        // Calculate expected balance: Opening + Sum(INCOME transactions)
        $salesSum = Transaction::where('cash_register_id', $register->id)
            ->where('type', 'INCOME')
            ->where('payment_status', 'PAID')
            ->sum('amount');
            
        $expectedBalance = $register->opening_balance + $salesSum;
        $physicalBalance = $request->closing_physical_balance;
        $discrepancy = $physicalBalance - $expectedBalance;

        $register->update([
            'closed_at' => now(),
            'closing_system_balance' => $expectedBalance,
            'closing_physical_balance' => $physicalBalance,
            'discrepancy' => $discrepancy,
            'status' => 'CLOSED'
        ]);

        return back()->with('success', 'Shift berhasil ditutup! Selisih: Rp ' . number_format($discrepancy, 0, ',', '.'));
    }
}
