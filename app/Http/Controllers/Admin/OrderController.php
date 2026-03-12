<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EcOrder;
use App\Models\EcOrderStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = EcOrder::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }
        $orders = $query->paginate(15);
        $stats = [
            'menunggu'          => EcOrder::where('status', 'menunggu_pembayaran')->count(),
            'perlu_diproses'    => EcOrder::where('status', 'perlu_diproses')->count(),
            'diproses'          => EcOrder::where('status', 'diproses')->count(),
            'dikirim'           => EcOrder::where('status', 'dikirim')->count(),
        ];
        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(EcOrder $order)
    {
        $order->load(['items', 'statusHistories.admin']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, EcOrder $order)
    {
        $request->validate([
            'status'          => 'required|in:perlu_diproses,diproses,dikirim,selesai,dibatalkan',
            'tracking_number' => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        $order->update([
            'status'          => $request->status,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number,
        ]);

        EcOrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => $request->status,
            'notes'      => $request->notes,
            'updated_by' => auth()->id(),
        ]);

        // Kirim notifikasi WA/Email ke pelanggan
        \App\Services\NotificationService::sendOrderUpdate($order);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function verifyPayment(Request $request, EcOrder $order)
    {
        $request->validate(['payment_proof' => 'nullable|image|max:2048']);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $order->update(['payment_proof' => $path]);
        }

        $order->update([
            'payment_status' => 'paid',
            'paid_at'        => now(),
            'status'         => 'perlu_diproses',
        ]);

        EcOrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => 'perlu_diproses',
            'notes'      => 'Pembayaran telah diverifikasi oleh admin.',
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }
}
