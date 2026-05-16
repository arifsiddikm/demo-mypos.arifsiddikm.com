<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categories
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('icon')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Menu items
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->string('image')->nullable();
                $table->boolean('is_available')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // Suppliers
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('contact_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Ingredients / Bahan
        if (!Schema::hasTable('ingredients')) {
            Schema::create('ingredients', function (Blueprint $table) {
                $table->id();
                $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
                $table->string('name');
                $table->string('unit');
                $table->decimal('stock', 10, 3)->default(0);
                $table->decimal('min_stock', 10, 3)->default(0);
                $table->decimal('cost_per_unit', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        // Menu-Ingredient pivot
        if (!Schema::hasTable('menu_ingredient')) {
            Schema::create('menu_ingredient', function (Blueprint $table) {
                $table->id();
                $table->foreignId('menu_id')->constrained()->onDelete('cascade');
                $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
                $table->decimal('quantity_used', 10, 3);
                $table->timestamps();
            });
        }

        // Inventory movements
        if (!Schema::hasTable('inventory_movements')) {
            Schema::create('inventory_movements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
                $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('type', ['in', 'out', 'adjustment']);
                $table->decimal('quantity', 10, 3);
                $table->decimal('cost_per_unit', 10, 2)->nullable();
                $table->string('notes')->nullable();
                $table->timestamp('movement_date');
                $table->timestamps();
            });
        }

        // Tables / Meja
        if (!Schema::hasTable('tables')) {
            Schema::create('tables', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('capacity')->default(4);
                $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
                $table->integer('pos_x')->default(0);
                $table->integer('pos_y')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Transactions
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('table_id')->nullable()->constrained()->onDelete('set null');
                $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in');
                $table->enum('status', ['open', 'hold', 'paid', 'cancelled'])->default('open');
                $table->enum('payment_method', ['cash', 'transfer', 'qris'])->nullable();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('tax', 12, 2)->default(0);
                $table->decimal('discount', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->decimal('change_amount', 12, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        // Transaction items
        if (!Schema::hasTable('transaction_items')) {
            Schema::create('transaction_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
                $table->foreignId('menu_id')->constrained()->onDelete('cascade');
                $table->string('menu_name');
                $table->decimal('price', 10, 2);
                $table->integer('quantity');
                $table->decimal('subtotal', 12, 2);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Printer settings
        if (!Schema::hasTable('printer_settings')) {
            Schema::create('printer_settings', function (Blueprint $table) {
                $table->id();
                $table->string('printer_name')->nullable();
                $table->string('printer_type')->default('thermal');
                $table->boolean('auto_print')->default(false);
                $table->string('paper_size')->default('58mm');
                $table->text('header_text')->nullable();
                $table->text('footer_text')->nullable();
                $table->timestamps();
            });
        }

        // Cafe settings
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('printer_settings');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('menu_ingredient');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('categories');
    }
};
