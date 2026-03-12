<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Karyawan (Pengganti User profil mendalam)
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nip')->unique();
            $table->string('nama_lengkap');
            $table->string('no_hp')->nullable();
            $table->string('departemen')->nullable();
            $table->string('jabatan')->nullable();
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->date('tanggal_masuk')->nullable();
            $table->integer('sisa_cuti')->default(12);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('foto_profil')->nullable();
            $table->boolean('has_face_registered')->default(false);
            $table->string('telegram_chat_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Izins (Persetujuan Cuti/Sakit)
        Schema::create('izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained()->onDelete('cascade');
            $table->enum('tipe', ['cuti', 'sakit', 'izin', 'lembur']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->string('bukti_path')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Jadwal Kerja
        Schema::create('jadwal_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('nama_jadwal');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->time('toleransi_keterlambatan')->default('00:00:00');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 4. Tabel Penggajian
        Schema::create('penggajians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained()->onDelete('cascade');
            $table->string('periode_bulan'); // YYYY-MM
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan', 15, 2)->default(0);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->decimal('lembur', 15, 2)->default(0);
            $table->decimal('total_gaji', 15, 2);
            $table->enum('status_pembayaran', ['pending', 'dibayar'])->default('pending');
            $table->date('tanggal_dibayar')->nullable();
            $table->string('slip_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penggajians');
        Schema::dropIfExists('jadwal_kerjas');
        Schema::dropIfExists('izins');
        Schema::dropIfExists('karyawans');
    }
};
