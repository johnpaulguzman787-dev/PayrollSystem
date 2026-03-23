<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sss_contributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('salary_from', 10, 2);
            $table->decimal('salary_to', 10, 2);
            $table->decimal('monthly_salary_credit', 10, 2);
            $table->decimal('employee_share', 10, 2);
            $table->decimal('employer_share', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['active','inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sss_contributions');
    }
};
