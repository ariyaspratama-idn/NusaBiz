<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('parent')->orderBy('code')->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $parentAccounts = Account::where('is_header', true)->get();
        $branches = \App\Models\Branch::where('is_active', true)->get();
        $users = \App\Models\User::all();
        return view('accounts.create', compact('parentAccounts', 'branches', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts,code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'type' => 'required|in:ASSET,LIABILITY,EQUITY,REVENUE,EXPENSE',
            'parent_id' => 'nullable|exists:accounts,id',
            'normal_balance' => 'required|in:DEBIT,KREDIT',
            'is_header' => 'boolean',
            'description' => 'nullable|string',
            'restricted_branch_id' => 'nullable|exists:branches,id',
            'monthly_budget' => 'nullable|numeric|min:0',
            'pic_id' => 'nullable|exists:users,id',
            'attachment_path' => 'nullable|string',
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('accounts', 'public');
            $validated['attachment_path'] = $path;
        }

        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }
}
