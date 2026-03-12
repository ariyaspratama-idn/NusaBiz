<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function profitLoss(Request $request)
    {
        $branchId = $request->input('branch_id');
        $division = $request->input('division');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Previous Period Calculation for Growth Metrics
        $startComp = \Carbon\Carbon::parse($startDate);
        $endComp = \Carbon\Carbon::parse($endDate);
        $days = $startComp->diffInDays($endComp) + 1;
        
        $prevEndDate = $startComp->copy()->subDay();
        $prevStartDate = $prevEndDate->copy()->subDays($days - 1);

        // Current Period Data
        $query = Transaction::query()
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($division, fn($q) => $q->where('division', $division))
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        $revenue = (clone $query)->whereHas('account', fn($q) => $q->where('type', 'REVENUE'))->sum('amount');
        
        // Split COGS (HPP) from general Expenses
        $hpp = (clone $query)->whereHas('account', fn($q) => $q->where('name', 'like', '%HPP%')->orWhere('name', 'like', '%Harga Pokok Penjualan%'))->sum('amount');
        $expense = (clone $query)->whereHas('account', fn($q) => $q->where('type', 'EXPENSE')
            ->where('name', 'not like', '%HPP%')
            ->where('name', 'not like', '%Harga Pokok Penjualan%')
        )->sum('amount');

        $grossProfit = $revenue - $hpp;
        $netProfit = $grossProfit - $expense;

        // Previous Period Data
        $prevQuery = Transaction::query()
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($division, fn($q) => $q->where('division', $division))
            ->whereBetween('transaction_date', [$prevStartDate->toDateString(), $prevEndDate->toDateString()]);

        $prevRevenue = (clone $prevQuery)->whereHas('account', fn($q) => $q->where('type', 'REVENUE'))->sum('amount');
        $prevHpp = (clone $prevQuery)->whereHas('account', fn($q) => $q->where('name', 'like', '%HPP%')->orWhere('name', 'like', '%Harga Pokok Penjualan%'))->sum('amount');
        $prevExpense = (clone $prevQuery)->whereHas('account', fn($q) => $q->where('type', 'EXPENSE')
            ->where('name', 'not like', '%HPP%')
            ->where('name', 'not like', '%Harga Pokok Penjualan%')
        )->sum('amount');

        $prevGrossProfit = $prevRevenue - $prevHpp;
        $prevNetProfit = $prevGrossProfit - $prevExpense;

        // Growth percentages
        $revGrowth = $prevRevenue > 0 ? (($revenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        $expGrowth = $prevExpense > 0 ? (($expense - $prevExpense) / $prevExpense) * 100 : 0;
        $netGrowth = $prevNetProfit != 0 ? (($netProfit - $prevNetProfit) / abs($prevNetProfit)) * 100 : 0;

        // Breakdown details
        $revenueDetails = (clone $query)->whereHas('account', fn($q) => $q->where('type', 'REVENUE'))
            ->selectRaw('account_id, sum(amount) as total')
            ->groupBy('account_id')->with('account')->get();

        $hppDetails = (clone $query)->whereHas('account', fn($q) => $q->where('name', 'like', '%HPP%')->orWhere('name', 'like', '%Harga Pokok Penjualan%'))
            ->selectRaw('account_id, sum(amount) as total')
            ->groupBy('account_id')->with('account')->get();

        $expenseDetails = (clone $query)->whereHas('account', fn($q) => $q->where('type', 'EXPENSE')
            ->where('name', 'not like', '%HPP%')
            ->where('name', 'not like', '%Harga Pokok Penjualan%')
        )
            ->selectRaw('account_id, sum(amount) as total')
            ->groupBy('account_id')->with('account')->get();

        $recentTransactions = (clone $query)->with(['branch', 'account'])->latest('transaction_date')->take(20)->get();

        // Dropdown data
        $branches = \App\Models\Branch::all();
        $divisions = Transaction::whereNotNull('division')->distinct()->pluck('division');

        if ($request->has('print')) {
            return view('reports.print_profit_loss', compact(
                'revenue', 'hpp', 'expense', 'grossProfit', 'netProfit',
                'prevRevenue', 'prevHpp', 'prevExpense', 'prevGrossProfit', 'prevNetProfit',
                'revGrowth', 'expGrowth', 'netGrowth',
                'revenueDetails', 'hppDetails', 'expenseDetails', 'recentTransactions', 'branches', 'divisions',
                'branchId', 'division', 'startDate', 'endDate'
            ));
        }

        return view('reports.profit_loss', compact(
            'revenue', 'hpp', 'expense', 'grossProfit', 'netProfit',
            'prevRevenue', 'prevHpp', 'prevExpense', 'prevGrossProfit', 'prevNetProfit',
            'revGrowth', 'expGrowth', 'netGrowth',
            'revenueDetails', 'hppDetails', 'expenseDetails', 'recentTransactions', 'branches', 'divisions',
            'branchId', 'division', 'startDate', 'endDate'
        ));
    }

    public function printProfitLoss(Request $request)
    {
        // Re-use logic or call it (for simplicity in demo, we'll duplicate or refactor later)
        // For now, let's just make sure it has the variables
        return $this->profitLoss($request); // We can modify profitLoss to detect if it's a print request
    }

    public function exportCsv(Request $request)
    {
        $branchId = $request->input('branch_id');
        $division = $request->input('division');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transactions = Transaction::with(['branch', 'account'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($division, fn($q) => $q->where('division', $division))
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('transaction_date', [$startDate, $endDate]))
            ->latest('transaction_date')
            ->get();

        $filename = "profit_loss_report_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Transaction No', 'Branch', 'Account', 'Type', 'Division', 'Amount', 'Description'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->transaction_date->format('Y-m-d'),
                    $tx->transaction_no,
                    $tx->branch->name,
                    $tx->account->name,
                    $tx->account->type,
                    $tx->division,
                    $tx->amount,
                    $tx->description,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
