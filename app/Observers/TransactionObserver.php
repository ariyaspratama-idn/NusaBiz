<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\JournalHeader;
use App\Models\JournalDetail;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            // 1. Create Journal Header
            $journal = JournalHeader::create([
                'journal_no' => 'JV-' . $transaction->transaction_no,
                'journal_date' => $transaction->transaction_date,
                'branch_id' => $transaction->branch_id,
                'description' => "Journal for transaction: " . $transaction->transaction_no . ". " . $transaction->description,
                'total_debit' => $transaction->amount,
                'total_credit' => $transaction->amount,
                'status' => 'POSTED', // Auto-post for simple transactions
                'reference_type' => 'TRANSACTION',
                'reference_id' => $transaction->id,
                'created_by' => $transaction->created_by,
                'posted_by' => $transaction->created_by,
                'posted_at' => now(),
            ]);

            // 2. Create Journal Details
            // Rule: Income => Debit Cash/Bank (transaction->account_id), Credit Revenue (target account)
            // Rule: Expense => Debit Expense (target account), Credit Cash/Bank (transaction->account_id)
            
            // Note: Since the migration only has ONE account_id in transactions, 
            // we assume it's the target account (Income/Expense account).
            // For Cash/Bank, we usually have a default or the user selects it.
            // In this simple implementation, let's assume we need a "Kas" account.
            
            $cashAccount = \App\Models\Account::where('code', '1101')->first(); // Kas Pusat

            if ($transaction->type === 'INCOME') {
                // Debit Cash
                JournalDetail::create([
                    'journal_header_id' => $journal->id,
                    'account_id' => $cashAccount->id,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => $transaction->description,
                ]);

                // Credit Income/Target Account
                JournalDetail::create([
                    'journal_header_id' => $journal->id,
                    'account_id' => $transaction->account_id,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => $transaction->description,
                ]);
            } else {
                // Debit Expense/Target Account
                JournalDetail::create([
                    'journal_header_id' => $journal->id,
                    'account_id' => $transaction->account_id,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => $transaction->description,
                ]);

                // Credit Cash
                JournalDetail::create([
                    'journal_header_id' => $journal->id,
                    'account_id' => $cashAccount->id,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => $transaction->description,
                ]);
            }

            // Link journal back to transaction
            $transaction->updateQuietly(['journal_header_id' => $journal->id]);
        });
    }
}
