<?php

namespace App\Services;

use App\Models\Permohonan;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    /**
     * Logika persetujuan berjenjang: Karyawan -> Admin Cabang (SOP) -> Admin Pusat (Payroll/Mutasi).
     */
    /**
     * Persetujuan oleh Kepala Cabang (Gatekeeper Cabang).
     */
    public function approveByBranchHead($modelType, $id, $evidencePath = null)
    {
        $modelClass = "App\\Models\\" . $modelType;
        $record = $modelClass::findOrFail($id);
        
        $user = auth()->user();
        if (!in_array($user->role, ['kepala-cabang', 'wakil-kepala-cabang', 'SUPER_ADMIN'])) {
            throw new \Exception("Hanya Kepala Cabang atau Wakil yang dapat memberikan persetujuan akhir cabang.");
        }

        $record->update([
            'branch_head_approved_at' => now(),
            'branch_head_approved_by' => $user->id,
            'evidence_path' => $evidencePath ?? $record->evidence_path,
            'status' => 'APPROVED_BY_BRANCH', // Custom status for the workflow
        ]);

        return $record;
    }

    public function reject($modelType, $id, $reason)
    {
        $modelClass = "App\\Models\\" . $modelType;
        $record = $modelClass::findOrFail($id);
        
        $record->update([
            'status' => 'REJECTED',
            'rejection_reason' => $reason,
            'branch_head_approved_at' => now(),
            'branch_head_approved_by' => auth()->id(),
        ]);

        return $record;
    }
}
