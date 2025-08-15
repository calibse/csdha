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
        Schema::create('event_students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', length: 20);
            $table->string('first_name', length: 50);
            $table->string('middle_name', length: 50)->nullable();
            $table->string('last_name', length: 50);
            $table->string('suffix_name', length: 50)->nullable();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('student_year_id')->constrained();
            $table->foreignId('student_section_id')->constrained();
            $table->string('email', length: 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_students');
    }
};
