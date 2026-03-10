<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\SopLog;
use Carbon\Carbon;

class ComplianceController extends Controller
{
    public function monitor(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $branches = Branch::with(['sops', 'sopLogs' => function($q) use ($date) {
            $q->where('date', $date);
        }])->where('is_active', true)->get();

        $complianceData = $branches->map(function($branch) use ($date) {
            $requiredSopsCount = $branch->sops->count();
            $completedSopsCount = $branch->sopLogs->where('status', 'DONE')->unique('sop_id')->count();
            
            $percentage = $requiredSopsCount > 0 ? round(($completedSopsCount / $requiredSopsCount) * 100) : 0;
            
            return [
                'branch' => $branch,
                'required' => $requiredSopsCount,
                'completed' => $completedSopsCount,
                'percentage' => $percentage,
                'logs' => $branch->sopLogs()->with('sop', 'user')->where('date', $date)->latest()->get()
            ];
        });

        return view('reports.compliance', compact('complianceData', 'date'));
    }

    public function stockMonitor(Request $request)
    {
        $branchId = $request->get('branch_id');
        $stocks = \App\Models\StockRequest::with(['branch', 'user'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest()
            ->paginate(20);
        
        $branches = Branch::all();
        return view('reports.stock_monitor', compact('stocks', 'branches', 'branchId'));
    }

    public function complaintMonitor(Request $request)
    {
        $branchId = $request->get('branch_id');
        $complaints = \App\Models\Complaint::with(['branch', 'user'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest()
            ->paginate(20);

        $branches = Branch::all();
        return view('reports.complaint_monitor', compact('complaints', 'branches', 'branchId'));
    }
}
