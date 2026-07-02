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
        $tables = ['users', 'variations', 'items', 'purches_books', 'sales_books'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->integer('sub_company_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'variations', 'items', 'purches_books', 'sales_books'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('sub_company_id');
            });
        }
    }
};
