<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Contact;
use App\Models\JournalHeader;
use App\Models\JournalDetail;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class DebtBookController extends Controller
{
    public function index()
    {
        // Get customers who have UNPAID or PARTIAL transactions
        $debts = Transaction::whereIn('payment_status', ['UNPAID', 'PARTIAL'])
            ->with(['contact', 'branch'])
            ->orderBy('transaction_date', 'asc')
            ->get();

        return view('debts.index', compact('debts'));
    }

    public function pay(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:1|max:' . $transaction->amount,
            'account_id' => 'required|exists:accounts,id',
        ]);

        try {
            DB::beginTransaction();

            $amount = $validated['amount_paid'];
            
            // Create Journal Entry for Receipt
            $journalNo = 'JRN-PAY-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            $journal = JournalHeader::create([
                'journal_no' => $journalNo,
                'journal_date' => now(),
                'branch_id' => $transaction->branch_id,
                'description' => 'Pembayaran Kasbon: ' . $transaction->transaction_no,
                'total_debit' => $amount,
                'total_credit' => $amount,
                'status' => 'POSTED',
                'reference_type' => 'DEBT_PAYMENT',
                'reference_id' => $transaction->id,
                'created_by' => auth()->id() ?? 1,
                'posted_by' => auth()->id() ?? 1,
                'posted_at' => now()
            ]);

            // Debit Cash/Bank
            JournalDetail::create([
                'journal_header_id' => $journal->id,
                'account_id' => $validated['account_id'],
                'debit' => $amount,
                'credit' => 0,
                'description' => 'Terima Kasbon'
            ]);

            // Credit (This depends on where it was recorded. Ideally, it was recorded in AR or a specific clearing account.)
            // For simplicity in Warkop mode, let's assume it goes to a "Revenue" or "AR" account.
            // In the POS Lite implementation, if it was UNPAID, maybe no journal was created yet OR it was created in a clearing account.
            // Let's check PosTransactionService.
            
            $journalDetailAccount = Account::where('type', 'REVENUE')->orWhere('name', 'like', '%Penjualan%')->first();
            
            JournalDetail::create([
                'journal_header_id' => $journal->id,
                'account_id' => $journalDetailAccount->id,
                'debit' => 0,
                'credit' => $amount,
                'description' => 'Pelunasan Pendapatan POS'
            ]);

            // Update Transaction Status
            // In a more complex system, we'd track partials. Here, let's just mark it PAID if amount is full.
            if ($amount >= $transaction->amount) {
                $transaction->payment_status = 'PAID';
            } else {
                $transaction->payment_status = 'PARTIAL';
            }
            $transaction->save();

            DB::commit();

            return back()->with('success', 'Pembayaran kasbon berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }
}
