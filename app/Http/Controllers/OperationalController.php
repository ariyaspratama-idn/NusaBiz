<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Sop;
use App\Models\SopLog;
use App\Models\Complaint;
use App\Models\StockRequest;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OperationalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Filter list Cabang yang bisa dipilih
        if ($user && $user->role !== \App\Models\User::ROLE_SUPER_ADMIN && $user->branch_id) {
            $branches = Branch::where('id', $user->branch_id)->where('is_active', true)->get();
            $selectedBranchId = $user->branch_id;
        } else {
            $branches = Branch::where('is_active', true)->get();
            $selectedBranchId = $request->get('branch_id') ?? ($branches->first()->id ?? null);
        }

        $selectedBranch = $selectedBranchId ? Branch::find($selectedBranchId) : null;
        
        // Fetch only SOPs assigned to this branch
        $sops = $selectedBranch ? $selectedBranch->sops()->where('is_active', true)->get() : collect();
        
        return view('operations.index', compact('branches', 'sops', 'selectedBranchId'));
    }

    public function attendance(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'type' => 'required|in:clock_in,clock_out',
        ]);

        $today = now()->toDateString();
        $attendance = Attendance::firstOrCreate([
            'user_id' => Auth::id() ?? 1, 
            'branch_id' => $validated['branch_id'],
            'date' => $today,
        ]);

        if ($validated['type'] == 'clock_in') {
            $attendance->update(['clock_in' => now()->toTimeString(), 'status' => 'PRESENT']);
        } else {
            $attendance->update(['clock_out' => now()->toTimeString()]);
        }

        return redirect()->back()->with('success', 'Attendance recorded: ' . ucfirst(str_replace('_', ' ', $validated['type'])));
    }

    public function sopLog(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'sop_id' => 'required|exists:sops,id',
            'status' => 'required|in:DONE,FAILED',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Optional evidence photo
        ]);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('sop_evidence', 'public') : null;

        SopLog::create([
            'user_id' => Auth::id() ?? 1,
            'branch_id' => $validated['branch_id'],
            'sop_id' => $validated['sop_id'],
            'status' => $validated['status'],
            'photo_path' => $photoPath,
            'notes' => $validated['notes'],
            'date' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'SOP compliance recorded successfully.');
    }

    public function complaint(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'description' => 'required|string',
            'photo' => 'required|image|max:2048', // Mandatory photo
        ]);

        $path = $request->file('photo')->store('complaints', 'public');

        Complaint::create([
            'user_id' => Auth::id() ?? 1,
            'branch_id' => $validated['branch_id'],
            'description' => $validated['description'],
            'photo_path' => $path,
            'date' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Complaint reported successfully.');
    }

    public function stockRequest(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'item_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'purpose' => 'nullable|string',
        ]);

        StockRequest::create([
            'user_id' => Auth::id() ?? 1,
            'branch_id' => $validated['branch_id'],
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'],
            'purpose' => $validated['purpose'],
        ]);

        return redirect()->back()->with('success', 'Stock request submitted.');
    }
}
