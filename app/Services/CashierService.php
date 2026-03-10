<?php

namespace App\Services;

use App\Models\CashierSession;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Exception;

class CashierService
{
    /**
     * Membuka sesi kasir baru.
     * Syarat: Sudah absen hari ini dan disetujui wakil/kepala cabang.
     */
    public function openSession($openingBalance, $description = null, $evidencePath = null)
    {
        $user = Auth::user();
        
        // 1. Cek Absensi Hari Ini
        $hasAttendance = Attendance::where('user_id', $user->id)
            ->where('date', now()->toDateString())
            ->exists();
            
        if (!$hasAttendance) {
            throw new Exception("Anda wajib melakukan absensi (Check-in) terlebih dahulu sebelum membuka kasir.");
        }

        // 2. Buat Sesi (Status PENDING_APPROVAL karena butuh persetujuan)
        return CashierSession::create([
            'user_id' => $user->id,
            'branch_id' => $user->branch_id,
            'opening_balance' => $openingBalance,
            'opened_at' => now(),
            'status' => 'PENDING_APPROVAL',
            'description' => $description,
            'evidence_path' => $evidencePath,
        ]);
    }

    /**
     * Menyetujui pembukaan/penutupan sesi kasir (oleh Kepala/Wakil Cabang).
     */
    public function approveSession($sessionId)
    {
        $session = CashierSession::findOrFail($sessionId);
        $user = Auth::user();

        if (!in_array($user->role, ['kepala-cabang', 'wakil-kepala-cabang', 'SUPER_ADMIN'])) {
            throw new Exception("Hanya Kepala atau Wakil Kepala Cabang yang dapat memberikan persetujuan kasir.");
        }

        $session->update([
            'status' => $session->closed_at ? 'CLOSED' : 'OPEN',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return $session;
    }

    /**
     * Menutup sesi kasir.
     */
    public function closeSession($sessionId, $closingBalance, $description = null, $evidencePath = null)
    {
        $session = CashierSession::findOrFail($sessionId);
        
        $session->update([
            'closing_balance' => $closingBalance,
            'closed_at' => now(),
            'status' => 'PENDING_APPROVAL', // Butuh approval lagi untuk tutup
            'description' => $description,
            'evidence_path' => $evidencePath,
        ]);

        return $session;
    }
}
