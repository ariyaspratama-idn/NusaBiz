<?php

namespace App\Http\Controllers;

use App\Models\EcProduct;
use App\Models\EcOrder;
use App\Models\EcOrderItem;
use App\Models\EcOrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        // Ambil cart dari session
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('storefront.catalog')->with('error', 'Keranjang belanja Anda kosong!');
        }

        return view('storefront.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'      => 'required|string|max:150',
            'customer_phone'     => 'required|string|max:20',
            'customer_email'     => 'nullable|email',
            'shipping_address'   => 'required|string',
            'shipping_district'  => 'required|string',
            'shipping_city'      => 'required|string',
            'shipping_province'  => 'required|string',
            'shipping_postal_code' => 'nullable|string|max:10',
            'shipping_type'      => 'required|in:nasional,ojek_online,kurir_internal',
            'shipping_courier'   => 'nullable|string',
            'shipping_service'   => 'nullable|string',
            'shipping_cost'      => 'required|numeric|min:0',
            'payment_method'     => 'required|in:transfer,midtrans,cod',
            'notes'              => 'nullable|string|max:500',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong!');
        }

        DB::beginTransaction();
        try {
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $total = $subtotal + $validated['shipping_cost'];

            $order = EcOrder::create([
                ...$validated,
                'order_number' => EcOrder::generateOrderNumber(),
                'user_id'      => auth()->id(),
                'subtotal'     => $subtotal,
                'discount'     => 0,
                'total'        => $total,
                'status'       => 'menunggu_pembayaran',
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $item) {
                EcOrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'] ?? null,
                    'variant_id'   => $item['variant_id'] ?? null,
                    'product_name' => $item['name'],
                    'variant_info' => $item['variant'] ?? null,
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                ]);

                // Kurangi stok
                if (isset($item['product_id'])) {
                    EcProduct::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                }
            }

            EcOrderStatusHistory::create([
                'order_id' => $order->id,
                'status'   => 'menunggu_pembayaran',
                'notes'    => 'Pesanan dibuat oleh pelanggan.',
            ]);

            session()->forget('cart');
            DB::commit();

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Pesanan berhasil! Segera selesaikan pembayaran Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success($orderNumber)
    {
        $order = EcOrder::where('order_number', $orderNumber)->firstOrFail();
        return view('storefront.checkout-success', compact('order'));
    }

    // Cart API endpoints
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:ec_products,id',
            'quantity'   => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:ec_product_variants,id',
        ]);

        $product = EcProduct::findOrFail($request->product_id);
        $cart = session('cart', []);
        $key = $product->id . '-' . ($request->variant_id ?? 'default');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'name'       => $product->name,
                'price'      => $product->effective_price,
                'quantity'   => $request->quantity,
                'image'      => $product->main_image,
            ];
        }

        session(['cart' => $cart]);
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json(['success' => true, 'cart_count' => $totalItems]);
    }

    public function removeFromCart(Request $request)
    {
        $key = $request->input('key');
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);
        return response()->json(['success' => true]);
    }
}
