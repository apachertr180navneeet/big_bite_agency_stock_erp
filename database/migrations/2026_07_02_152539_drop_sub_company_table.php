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
        Schema::dropIfExists('sub_company');
        Schema::dropIfExists('advance_salary');
        Schema::dropIfExists('emp_salary');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
