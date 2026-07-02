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
        Schema::create('emp_salary', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('slarly_mounth')->nullable(); // intentional typo match from controller
            $table->string('total_working_day')->nullable();
            $table->string('total_present_day')->nullable();
            $table->decimal('diduction_amount', 15, 2)->default(0);
            $table->decimal('diduction_amountfromadvance', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('salary_to')->nullable();
            $table->decimal('insentive_point', 15, 2)->default(0);
            $table->string('pay_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_salary');
    }
};
