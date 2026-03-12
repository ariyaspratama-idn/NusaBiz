<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeSession = null;
        
        if ($user) {
            // Cek Sesi Kasir yang aktif
            $activeSession = \App\Models\CashierSession::where('user_id', $user->id)
                                ->where('status', 'OPEN')
                                ->whereNull('closed_at')
                                ->first();
        }

        // Ambil data pendukung (branches/accounts)
        // Jika belum login, gunakan default (Cabang Utama)
        if ($user && $user->role !== User::ROLE_SUPER_ADMIN && $user->branch_id) {
            $branches = Branch::where('id', $user->branch_id)->where('is_active', true)->get();
        } else {
            $branches = Branch::where('is_active', true)->get();
        }

        $accounts = Account::where('is_header', false)->get();
        $contacts = \App\Models\Contact::all();

        $defaultBranch = ($user && $user->branch_id) ? Branch::find($user->branch_id) : $branches->first();
        $defaultAccount = $accounts->where('type', 'CASH')->first() ?? $accounts->first();

        return view('pos.index', compact('branches', 'accounts', 'defaultBranch', 'defaultAccount', 'contacts', 'activeSession'));
    }

    /**
     * Membuka Sesi Kasir Baru dengan Validasi Absensi & Shift
     */
    public function openSession(Request $request)
    {
        $request->validate([
            'cashier_nip' => 'required|string',
            'supervisor_nip' => 'required|string',
            'shift' => 'required|in:Pagi,Siang,Malam',
            'opening_balance' => 'required|numeric|min:0',
        ]);

        // 1. Cari Karyawan Berdasarkan NIP
        $cashierEmp = \App\Models\Karyawan::where('nip', $request->cashier_nip)->first();
        $supervisorEmp = \App\Models\Karyawan::where('nip', $request->supervisor_nip)->first();

        if (!$cashierEmp || !$supervisorEmp) {
            return response()->json(['success' => false, 'message' => 'NIP Kasir atau Penanggung Jawab tidak ditemukan.'], 422);
        }

        $cashier = $cashierEmp->user;
        $supervisor = $supervisorEmp->user;

        if (!$cashier || !$supervisor) {
            return response()->json(['success' => false, 'message' => 'User terkait NIP tidak ditemukan.'], 422);
        }

        // 2. Cek Role Supervisor
        if (!$supervisor->isBranchHead()) { // isBranchHead usually checks roles like 'kepala-cabang'
            return response()->json(['success' => false, 'message' => 'Penanggung Jawab harus Kepala/Wakil Toko.'], 422);
        }

        // 3. Validasi Absensi
        $today = now()->toDateString();
        
        $cashierAtt = \App\Models\Absensi::where('karyawan_id', $cashierEmp->id)
            ->where('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->first();
            
        $supervisorAtt = \App\Models\Absensi::where('karyawan_id', $supervisorEmp->id)
            ->where('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->first();

        if (!$cashierAtt) {
            return response()->json(['success' => false, 'message' => 'Kasir (' . $cashierEmp->nama_lengkap . ') belum absen Masuk hari ini.'], 422);
        }
        if (!$supervisorAtt) {
            return response()->json(['success' => false, 'message' => 'Penanggung Jawab (' . $supervisorEmp->nama_lengkap . ') belum absen Masuk hari ini.'], 422);
        }

        // 4. Otomatis Login Kasir
        \Illuminate\Support\Facades\Auth::login($cashier);

        // 5. Buat Sesi Kasir
        $session = \App\Models\CashierSession::create([
            'user_id' => $cashier->id,
            'branch_id' => $cashier->branch_id,
            'opening_balance' => $request->opening_balance,
            'shift' => $request->shift,
            'status' => 'OPEN',
            'opened_at' => now(),
            'supervisor_id' => $supervisor->id,
            'supervisor_nip' => $request->supervisor_nip
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Akses POS Berhasil! Selamat bertugas.',
            'session' => $session
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:1',
            'branch_id' => 'required|exists:branches,id',
            'account_id' => 'required|exists:accounts,id',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        try {
            // Sederhanakan deskripsi untuk menyimpan semua item
            $itemDetails = collect($request->items)->map(function($item) {
                return "{$item['name']} (x{$item['quantity']})";
            })->implode(', ');
            $description = "POS Multi-Item: " . substr($itemDetails, 0, 200);

            $posService = new \App\Services\PosTransactionService();
            $transaction = $posService->createPosTransaction([
                'branch_id' => $validated['branch_id'],
                'account_id' => $validated['account_id'],
                'total' => $validated['total'],
                'description' => $description,
                'items' => $request->items,
                'payment_status' => $request->input('payment_status', 'PAID'),
                'contact_id' => $request->input('contact_id', null),
                'cash_register_id' => $request->input('cash_register_id', null)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Super POS berhasil disimpan!',
                'data' => [
                    'transaction_no' => $transaction->transaction_no,
                    'total_bayar' => $validated['total'],
                    'item_count' => count($request->items)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Super POS Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
