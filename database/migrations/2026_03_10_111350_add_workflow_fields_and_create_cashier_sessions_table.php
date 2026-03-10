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
        // 1. Add evidence and approval fields to existing tables
        $tablesToUpdate = ['stock_requests', 'journal_headers', 'transactions'];
        
        foreach ($tablesToUpdate as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'evidence_path')) {
                    $anchor = Schema::hasColumn($tableName, 'description') ? 'description' : (Schema::hasColumn($tableName, 'reason') ? 'reason' : null);
                    if ($anchor) {
                        $table->string('evidence_path')->nullable()->after($anchor);
                    } else {
                        $table->string('evidence_path')->nullable();
                    }
                }
                if (!Schema::hasColumn($tableName, 'branch_head_approved_at')) {
                    $table->timestamp('branch_head_approved_at')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'branch_head_approved_by')) {
                    $table->unsignedBigInteger('branch_head_approved_by')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable();
                }
            });
        }

        // 2. Create cashier_sessions table
        Schema::create('cashier_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->string('status')->default('PENDING_APPROVAL'); // OPEN, CLOSED, PENDING_APPROVAL
            $table->string('evidence_path')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashier_sessions');

        $tablesToUpdate = ['stock_requests', 'journal_headers', 'transactions'];
        foreach ($tablesToUpdate as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['evidence_path', 'branch_head_approved_at', 'branch_head_approved_by', 'rejection_reason']);
            });
        }
    }
};
