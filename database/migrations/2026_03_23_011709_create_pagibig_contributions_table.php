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
        Schema::create('pagibig_contributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('salary_cap', 10, 2);
            $table->decimal('employee_rate_low', 5, 2);
            $table->decimal('employee_rate_high', 5, 2);
            $table->decimal('salary_threshold', 10, 2);
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagibig_contributions');
    }
};
