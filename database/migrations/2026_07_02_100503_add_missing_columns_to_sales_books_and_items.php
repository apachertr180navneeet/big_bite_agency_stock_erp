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
        Schema::table('sales_books', function (Blueprint $table) {
            $table->string('payment_type')->nullable();
            $table->string('transport')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('cess', 10, 2)->nullable();
            $table->string('sales_return')->default('0');
        });

        Schema::table('sales_book_items', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->decimal('cess', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_books', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'transport', 'vehicle_no', 'discount_value', 'cess', 'sales_return']);
        });

        Schema::table('sales_book_items', function (Blueprint $table) {
            $table->dropColumn(['category', 'cess']);
        });
    }
};
