<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BankStatement;
use App\Models\Transaction;

class BankReconciliationController extends Controller
{
    public function index()
    {
        $unreconciledStatements = BankStatement::where('status', 'UNRECONCILED')->orderBy('date', 'desc')->get();
        $reconciledStatements = BankStatement::where('status', 'RECONCILED')->orderBy('date', 'desc')->take(10)->get();
        
        return view('reconciliation.index', compact('unreconciledStatements', 'reconciledStatements'));
    }

    public function upload(Request $request)
    {
        $request->validate(['statement' => 'required|file|mimes:csv,txt']);
        
        $file = $request->file('statement');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            BankStatement::create([
                'date' => $data[0],
                'description' => $data[1],
                'amount' => $data[2],
                'reference' => $data[3] ?? null,
                'status' => 'UNRECONCILED'
            ]);
        }
        
        fclose($handle);
        return back()->with('success', 'Rekening koran berhasil diunggah.');
    }

    public function autoMatch()
    {
        $statements = BankStatement::where('status', 'UNRECONCILED')->get();
        $matchedCount = 0;

        foreach ($statements as $statement) {
            // Find transaction with same date and same amount (+/- 1 day flexibility)
            $transaction = Transaction::whereBetween('transaction_date', [
                $statement->date->subDay()->toDateString(), 
                $statement->date->addDay()->toDateString()
            ])
            ->where('amount', $statement->amount)
            ->whereDoesntHave('bankStatement') // Skip already matched transactions
            ->first();

            if ($transaction) {
                $statement->update([
                    'transaction_id' => $transaction->id,
                    'status' => 'RECONCILED'
                ]);
                $matchedCount++;
            }
        }

        return back()->with('success', "$matchedCount transaksi berhasil dicocokkan secara otomatis.");
    }
}
