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
        Schema::table('event_students', function (Blueprint $table) {
            //$table->foreignId('student_section_id')->constrained();
            $table->foreignId('student_section_id')->nullable();
            $table->foreign('student_section_id')->references('id')
                ->on('student_sections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_students', function (Blueprint $table) {
            //
        });
    }
};
