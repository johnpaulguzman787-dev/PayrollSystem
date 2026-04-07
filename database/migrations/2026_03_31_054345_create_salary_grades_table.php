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
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id();
            $table->string('grade_code');
            $table->string('level_name');
            $table->decimal('monthly_basic_salary', 12, 2);
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('salary_grades');
    }
};
