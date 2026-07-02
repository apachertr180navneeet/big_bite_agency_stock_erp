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
        Schema::table('purches_books', function (Blueprint $table) {
            $table->string('transport_number')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('discount_value')->nullable();
            $table->string('cess')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purches_books', function (Blueprint $table) {
            $table->dropColumn(['transport_number', 'payment_type', 'discount_value', 'cess']);
        });
    }
};
