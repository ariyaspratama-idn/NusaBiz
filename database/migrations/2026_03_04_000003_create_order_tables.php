<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pesanan (Order)
        Schema::create('ec_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // null = guest checkout
            // Informasi Pembeli
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            // Alamat Pengiriman
            $table->string('shipping_address');
            $table->string('shipping_district'); // Kecamatan
            $table->string('shipping_city');     // Kota/Kabupaten
            $table->string('shipping_province')->nullable();
            $table->string('shipping_postal_code')->nullable();
            // Pengiriman
            $table->enum('shipping_type', ['nasional', 'ojek_online', 'kurir_internal'])->default('nasional');
            $table->string('shipping_courier')->nullable(); // JNE, J&T, GoSend, dll
            $table->string('shipping_service')->nullable(); // REG, YES, dll
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->string('tracking_number')->nullable(); // Nomor resi
            // Pembayaran
            $table->enum('payment_method', ['transfer', 'midtrans', 'cod'])->default('transfer');
            $table->string('payment_token')->nullable(); // Midtrans snap token
            $table->string('payment_transaction_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_proof')->nullable(); // Bukti transfer manual
            // Status Pesanan
            $table->enum('status', ['menunggu_pembayaran', 'perlu_diproses', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu_pembayaran');
            // Harga
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Item Pesanan
        Schema::create('ec_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ec_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('ec_products')->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('ec_product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_info')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // History Status Pesanan
        Schema::create('ec_order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ec_orders')->cascadeOnDelete();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_order_status_histories');
        Schema::dropIfExists('ec_order_items');
        Schema::dropIfExists('ec_orders');
    }
};
