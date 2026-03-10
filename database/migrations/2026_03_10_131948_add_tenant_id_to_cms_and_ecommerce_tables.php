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
        $tables = [
            'cms_articles', 'cms_testimonials', 'cms_portfolios', 'cms_settings',
            'product_categories', 'ec_products', 'ec_product_variants', 'ec_product_images',
            'chat_sessions', 'chat_settings'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('tenant_id')->after('id')->nullable()->constrained('tenants')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'cms_articles', 'cms_testimonials', 'cms_portfolios', 'cms_settings',
            'product_categories', 'ec_products', 'ec_product_variants', 'ec_product_images',
            'chat_sessions', 'chat_settings'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign([ 'tenant_id' ]);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
