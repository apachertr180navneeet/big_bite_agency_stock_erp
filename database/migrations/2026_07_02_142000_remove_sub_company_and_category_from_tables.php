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
        Schema::dropIfExists('sub_companies');

        $tables = ['users', 'variations', 'items', 'purches_books', 'sales_books'];
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'sub_company_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('sub_company_id');
                });
            }
        }

        if (Schema::hasColumn('purches_book_items', 'category')) {
            Schema::table('purches_book_items', function (Blueprint $t) {
                $t->dropColumn('category');
            });
        }

        if (Schema::hasColumn('sales_book_items', 'category')) {
            Schema::table('sales_book_items', function (Blueprint $t) {
                $t->dropColumn('category');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('sub_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('status')->default('1');
            $table->timestamps();
        });

        $tables = ['users', 'variations', 'items', 'purches_books', 'sales_books'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->integer('sub_company_id')->nullable();
            });
        }

        Schema::table('purches_book_items', function (Blueprint $t) {
            $t->string('category')->nullable();
        });

        Schema::table('sales_book_items', function (Blueprint $t) {
            $t->string('category')->nullable();
        });
    }
};
