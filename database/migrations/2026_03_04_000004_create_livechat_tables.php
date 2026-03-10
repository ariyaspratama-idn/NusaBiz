<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sesi Chat
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_key')->unique(); // Identifikasi guest
            $table->string('visitor_name')->nullable();
            $table->string('visitor_email')->nullable();
            $table->string('visitor_phone')->nullable();
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });

        // Pesan Chat
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('chat_sessions')->cascadeOnDelete();
            $table->string('sender_type'); // 'visitor' or 'admin'
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Pengaturan Chat (Jam Operasional)
        Schema::create('chat_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_online')->default(true);
            $table->json('operating_hours')->nullable(); // {"mon": {"open":"08:00","close":"17:00"}, ...}
            $table->string('offline_message', 300)->default('Kami sedang offline. Tinggalkan pesan Anda, kami akan membalas segera!');
            $table->string('welcome_message', 300)->default('Halo! Ada yang bisa kami bantu? 😊');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_settings');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
