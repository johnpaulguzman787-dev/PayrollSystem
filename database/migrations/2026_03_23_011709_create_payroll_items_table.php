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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();

            // FK to payroll_records
            $table->unsignedBigInteger('payroll_record_id');
            $table->foreign('payroll_record_id')
                  ->references('id')
                  ->on('payroll_records')
                  ->cascadeOnDelete();

            $table->enum('type', ['earning', 'deduction']);
            $table->string('name', 100);
            $table->decimal('amount', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};