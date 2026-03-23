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
        Schema::create('tax_contributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('income_from', 12, 2);
            $table->decimal('income_to', 12, 2)->nullable();
            $table->decimal('base_tax', 12, 2);
            $table->decimal('tax_rate', 5, 4);
            $table->decimal('excess_over', 12, 2);
            $table->enum('status', ['active','inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_contributions');
    }
};
