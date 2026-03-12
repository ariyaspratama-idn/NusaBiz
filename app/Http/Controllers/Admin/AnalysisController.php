<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EcOrder;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    /**
     * Overview Analisis Lanjutan.
     */
    public function overview()
    {
        // Tren Pendapatan & Beban Gaji 6 Bulan Terakhir
        $trenPendapatan = EcOrder::select(
            DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as bulan'),
            DB::raw('SUM(total) as pendapatan')
        )
        ->where('payment_status', 'paid')
        ->where('paid_at', '>=', now()->subMonths(6))
        ->groupBy('bulan')
        ->get();

        $bebanGaji = \App\Models\Penggajian::select(
            DB::raw('periode_bulan as bulan'),
            DB::raw('SUM(total_gaji) as total_beban')
        )
        ->where('status_pembayaran', 'paid')
        ->where('periode_bulan', '>=', now()->subMonths(6)->format('Y-m'))
        ->groupBy('bulan')
        ->get();

        // Top Kategori Produk
        $topKategori = DB::table('ec_order_items')
            ->join('ec_products', 'ec_order_items.ec_product_id', '=', 'ec_products.id')
            ->join('product_categories', 'ec_products.category_id', '=', 'product_categories.id')
            ->select('product_categories.name', DB::raw('SUM(ec_order_items.quantity) as total_terjual'))
            ->groupBy('product_categories.name')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        return view('admin.analysis.overview', compact('trenPendapatan', 'bebanGaji', 'topKategori'));
    }

    /**
     * Analisis Pemeliharaan (Asset Maintenance).
     */
    public function maintenanceAnalytics()
    {
        // Mendapatkan data booking servis pemeliharaan
        $oilChanges = DB::table('bookings')
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->select(
                'vehicles.plate_number',
                'bookings.date',
                'bookings.notes'
            )
            ->where('bookings.notes', 'like', '%oli%')
            ->orderBy('bookings.date', 'desc')
            ->get();

        return view('admin.analysis.oil_change', compact('oilChanges'));
    }

    /**
     * Webhook Komplain Otomatis (Google Maps / External).
     */
    public function handleWebhookComplaint(Request $request)
    {
        // Token sederhana untuk demo
        if ($request->header('X-Webhook-Token') !== config('services.webhook.token', 'nusa-secret-123')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $complaint = \App\Models\Complaint::create([
            'branch_id' => $request->branch_id,
            'description' => "[OTOMATIS-" . strtoupper($request->source) . "] " . $request->description,
            'date' => now()->toDateString(),
            'status' => 'OPEN',
            'user_id' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true, 'id' => $complaint->id]);
    }
}
