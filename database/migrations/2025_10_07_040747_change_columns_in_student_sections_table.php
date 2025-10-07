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
        Schema::table('student_sections', function (Blueprint $table) {
            $table->softDeletes();
            $table->dropUnique(['section']);
            $table->tinyInteger('unarchived')
                ->storedAs('if(deleted_at is null, 1, null)');
            $table->unique(['section', 'unarchived']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_sections', function (Blueprint $table) {
            //
        });
    }
};
