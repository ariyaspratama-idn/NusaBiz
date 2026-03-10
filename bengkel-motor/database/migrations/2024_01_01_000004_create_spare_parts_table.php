<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('category', 50);
            $table->string('sku', 50)->unique();
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->string('supplier', 100)->nullable();
            $table->integer('min_stock')->default(5)->comment('Minimum stock for alert');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
