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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_plate', 20)->unique();
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->year('year');
            $table->string('color', 30)->nullable();
            $table->string('vin_number', 50)->nullable();
            $table->integer('current_odometer')->default(0);
            $table->integer('last_oil_change_km')->nullable();
            $table->date('last_oil_change_date')->nullable();
            $table->integer('oil_change_interval_months')->default(1)->comment('1 or 2 months');
            $table->date('next_oil_change_date')->nullable();
            $table->timestamp('first_reminder_sent_at')->nullable();
            $table->timestamp('second_reminder_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
