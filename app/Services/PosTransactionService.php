<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\JournalHeader;
use App\Models\JournalDetail;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class PosTransactionService
{
    public function createPosTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Main Transaction
            $transaction = Transaction::create([
                'transaction_no' => Transaction::generateUniqueNo(),
                'transaction_date' => now(),
                'branch_id' => $data['branch_id'],
                'type' => 'INCOME',
                'account_id' => $data['account_id'],
                'amount' => $data['total'],
                'description' => $data['description'],
                'created_by' => auth()->id() ?? 1,
                'payment_status' => $data['payment_status'] ?? 'PAID',
                'cash_register_id' => $data['cash_register_id'] ?? null,
                'contact_id' => $data['contact_id'] ?? null,
            ]);

            // 2. Double Entry Jurnal Accounting
            $revenueAccount = Account::where('type', 'REVENUE')->orWhere('name', 'like', '%Penjualan%')->first();
            $journalHeaderId = null;

            if ($revenueAccount && ($data['payment_status'] ?? 'PAID') === 'PAID') {
                $journalNo = 'JRN-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
                $journal = JournalHeader::create([
                    'journal_no' => $journalNo,
                    'journal_date' => now(),
                    'branch_id' => $data['branch_id'],
                    'description' => 'Jurnal Penjualan POS: ' . $transaction->transaction_no,
                    'total_debit' => $data['total'],
                    'total_credit' => $data['total'],
                    'status' => 'POSTED',
                    'reference_type' => 'POS_TRANSACTION',
                    'reference_id' => $transaction->id,
                    'created_by' => auth()->id() ?? 1,
                    'posted_by' => auth()->id() ?? 1,
                    'posted_at' => now()
                ]);

                $journalHeaderId = $journal->id;

                // Debit (Kas)
                JournalDetail::create([
                    'journal_header_id' => $journal->id,
                    'account_id' => $data['account_id'],
                    'debit' => $data['total'],
                    'credit' => 0,
                    'description' => 'Kas Masuk dari POS'
                ]);

                // Credit (Penjualan/Pendapatan)
                JournalDetail::create([
                    'journal_header_id' => $journal->id,
                    'account_id' => $revenueAccount->id,
                    'debit' => 0,
                    'credit' => $data['total'],
                    'description' => 'Pendapatan Penjualan POS'
                ]);

                $transaction->update(['journal_header_id' => $journal->id]);
            }

            // 3. Stock Movement
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $product = null;
                    if (isset($item['product_id'])) {
                        $product = Product::find($item['product_id']);
                    } else if (isset($item['name'])) {
                        $product = Product::where('name', $item['name'])->first();
                    }

                    if ($product) {
                        $qty = $item['quantity'] ?? 1;
                        $price = $item['price'] ?? 0;
                        $this->deductStock($product, $qty, $data['branch_id'], $transaction->id, $journalHeaderId);
                    }
                }
            }

            return $transaction;
        });
    }

    private function deductStock($product, $qty, $branchId, $transactionId, $journalHeaderId)
    {
        // Check if product has a recipe (BoM)
        $recipes = $product->recipes;

        if ($recipes->count() > 0) {
            // Deduct materials instead of the product itself
            foreach ($recipes as $recipe) {
                $material = $recipe->material;
                $neededQty = $recipe->quantity * $qty;
                $this->deductStock($material, $neededQty, $branchId, $transactionId, $journalHeaderId);
            }
        } else {
            // Direct deduction for simple products
            StockMovement::create([
                'movement_no' => 'MV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3))),
                'movement_date' => now(),
                'branch_id' => $branchId,
                'product_id' => $product->id,
                'type' => 'OUT',
                'quantity' => $qty,
                'unit_price' => $product->purchase_price ?? 0,
                'total_value' => $qty * ($product->purchase_price ?? 0),
                'reference_type' => 'POS_SALES',
                'reference_id' => $transactionId,
                'journal_header_id' => $journalHeaderId
            ]);
        }
    }
}
