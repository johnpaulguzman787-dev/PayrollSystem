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
        Schema::create('payroll_items_settings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('name', 100);

            $table->enum('category', ['late','undertime','overtime','holiday','leave','absent']);
            $table->enum('type', ['earning','deduction']);
            $table->enum('basis', ['per_day','per_hour','per_minute']);

            $table->decimal('multiplier', 6, 2)->default(1);
            $table->boolean('is_paid')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items_settings');
    }
};
