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
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignId('restricted_branch_id')->nullable()->after('parent_id')->constrained('branches')->nullOnDelete();
            $table->decimal('monthly_budget', 15, 2)->nullable()->after('description');
            $table->foreignId('pic_id')->nullable()->after('monthly_budget')->constrained('users')->nullOnDelete();
            $table->string('attachment_path')->nullable()->after('pic_id');
            $table->decimal('default_tax_rate', 5, 2)->nullable()->after('attachment_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['restricted_branch_id']);
            $table->dropForeign(['pic_id']);
            $table->dropColumn(['restricted_branch_id', 'monthly_budget', 'pic_id', 'attachment_path', 'default_tax_rate']);
        });
    }
};
