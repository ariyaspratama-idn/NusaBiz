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
        // 1. Roles & Permissions
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('module');
            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
        });

        // 2. Company & Branches
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_id')->nullable();
            $table->date('fiscal_year_start')->default('2024-01-01');
            $table->timestamps();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['PUSAT', 'CABANG'])->default('CABANG');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Chart of Accounts
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('normal_balance', ['DEBIT', 'KREDIT']);
            $table->boolean('is_header')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('accounts')->nullOnDelete();
        });

        Schema::create('account_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('branch_id')->constrained();
            $table->string('period'); // YYYY-MM
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('ending_balance', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['account_id', 'branch_id', 'period']);
        });

        // 4. Journals
        Schema::create('journal_headers', function (Blueprint $table) {
            $table->id();
            $table->string('journal_no')->unique();
            $table->date('journal_date');
            $table->foreignId('branch_id')->constrained();
            $table->text('description')->nullable();
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->enum('status', ['DRAFT', 'POSTED', 'REVERSED'])->default('DRAFT');
            $table->string('reference_type')->nullable(); // MANUAL, SALES, PURCHASE, etc
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('journal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_header_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. Products & Inventory
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // PRODUCT, ASSET, EXPENSE
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained();
            $table->string('unit')->default('pcs');
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->foreignId('account_id_inventory')->nullable()->constrained('accounts'); // Persediaan
            $table->foreignId('account_id_cogs')->nullable()->constrained('accounts'); // HPP
            $table->foreignId('account_id_sales')->nullable()->constrained('accounts'); // Penjualan
            $table->integer('min_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('stock_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity')->default(0);
            $table->decimal('average_cost', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['branch_id', 'product_id']);
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('movement_no')->unique();
            $table->date('movement_date');
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->enum('type', ['IN', 'OUT', 'ADJUSTMENT']);
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);
            $table->string('reference_type')->nullable(); // PURCHASE, SALE, TRANSFER
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('journal_header_id')->nullable()->constrained();
            $table->timestamps();
        });

        // 6. Contacts (Partners)
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['CUSTOMER', 'SUPPLIER', 'BOTH']);
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 7. Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->unique();
            $table->date('transaction_date');
            $table->foreignId('branch_id')->constrained();
            $table->enum('type', ['INCOME', 'EXPENSE']);
            $table->foreignId('contact_id')->nullable()->constrained();
            $table->foreignId('account_id')->constrained(); // Account lawan kas/bank
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->foreignId('journal_header_id')->nullable()->constrained();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_balances');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('journal_details');
        Schema::dropIfExists('journal_headers');
        Schema::dropIfExists('account_balances');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
