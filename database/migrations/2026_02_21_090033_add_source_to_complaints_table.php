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
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('source')->default('MANUAL'); // MANUAL, GOOGLE_MAPS, WHATSAPP, etc
            $table->string('external_id')->nullable()->index();
            $table->string('external_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['source', 'external_id', 'external_url']);
        });
    }
};
