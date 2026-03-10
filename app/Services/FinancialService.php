<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class FinancialService
{
    /**
     * Akumulasi laba rugi dari berbagai cabang dan tenant.
     */
    public function getProfitLoss($tenantId, $branchId = null, $startDate = null, $endDate = null)
    {
        $query = Transaction::where('tenant_id', $tenantId);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $income = (clone $query)->where('type', 'income')->sum('total_amount');
        $expense = (clone $query)->where('type', 'expense')->sum('total_amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'profit_loss' => $income - $expense,
        ];
    }

    /**
     * Mencatat transaksi POS secara otomatis ke Buku Besar.
     */
    public function recordTransaction($data)
    {
        return DB::transaction(function () use ($data) {
            $transaction = Transaction::create($data);
            
            // Logika Double-Entry Accounting bisa ditambahkan di sini
            
            return $transaction;
        });
    }
}
