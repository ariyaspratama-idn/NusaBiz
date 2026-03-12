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
        Schema::table('cashier_sessions', function (Blueprint $table) {
            $table->string('shift')->nullable()->after('status'); // Pagi, Siang, Malam
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->after('shift');
            $table->string('supervisor_nip')->nullable()->after('supervisor_id');
        });
    }

    public function down(): void
    {
        Schema::table('cashier_sessions', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn(['shift', 'supervisor_id', 'supervisor_nip']);
        });
    }
};
