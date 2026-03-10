<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Produk
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Produk E-Commerce
        Schema::create('ec_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->string('main_image')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('sale_price', 15, 2)->nullable(); // Harga coret/promo
            $table->integer('stock')->default(0);
            $table->integer('min_stock_alert')->default(5); // Stok kritis
            $table->decimal('weight', 8, 2)->default(0); // dalam gram, untuk ongkir
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Gambar Tambahan Produk
        Schema::create('ec_product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('ec_products')->cascadeOnDelete();
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Varian Produk (Warna, Ukuran, dll)
        Schema::create('ec_product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('ec_products')->cascadeOnDelete();
            $table->string('variant_name'); // e.g. "Warna", "Ukuran"
            $table->string('variant_value'); // e.g. "Merah", "XL"
            $table->decimal('price_modifier', 15, 2)->default(0); // tambahan harga
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_product_variants');
        Schema::dropIfExists('ec_product_images');
        Schema::dropIfExists('ec_products');
        Schema::dropIfExists('product_categories');
    }
};
