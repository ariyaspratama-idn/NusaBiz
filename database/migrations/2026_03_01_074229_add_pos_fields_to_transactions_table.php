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
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('payment_status', ['PAID', 'UNPAID', 'PARTIAL'])->default('PAID')->after('amount');
            $table->foreignId('cash_register_id')->nullable()->constrained()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['cash_register_id']);
            $table->dropColumn(['payment_status', 'cash_register_id']);
        });
    }
};
