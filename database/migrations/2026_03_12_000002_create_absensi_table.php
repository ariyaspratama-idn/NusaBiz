<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop tabel lama jika ada agar bersih
        Schema::dropIfExists('attendances');

        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('lat_masuk')->nullable();
            $table->string('lon_masuk')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('lat_pulang')->nullable();
            $table->string('lon_pulang')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'lembur', 'izin', 'alpa'])->default('hadir');
            $table->integer('menit_lembur')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensis');
    }
};
