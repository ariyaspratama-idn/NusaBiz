<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CashierSession;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCashierSession
{
    /**
     * Pastikan Kasir memiliki sesi yang AKTIF dan DISETUJUI sebelum bisa bertransaksi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Hanya berlaku untuk role kasir
        if ($user && $user->role === 'kasir') {
            $activeSession = CashierSession::where('user_id', $user->id)
                ->where('status', 'OPEN')
                ->whereNull('closed_at')
                ->first();

            if (!$activeSession) {
                return redirect()->route('admin.dashboard')->with('error', 'Anda harus membuka sesi kasir dan mendapatkan persetujuan atasan sebelum melakukan transaksi.');
            }
        }

        return $next($request);
    }
}
