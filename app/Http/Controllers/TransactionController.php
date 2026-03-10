<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['branch', 'account', 'contact'])->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::where('is_header', false)->get();
        $branches = Branch::where('is_active', true)->get();
        $contacts = Contact::all();
        return view('transactions.create', compact('accounts', 'branches', 'contacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'type' => 'required|in:INCOME,EXPENSE',
            'contact_id' => 'nullable|exists:contacts,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['transaction_no'] = Transaction::generateUniqueNo();
        $validated['created_by'] = auth()->id();

        DB::transaction(function () use ($validated) {
            $transaction = Transaction::create($validated);
            // Business Logic: Automate Journal creation will be handled by Observer/Service
        });

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded successfully.');
    }
}
