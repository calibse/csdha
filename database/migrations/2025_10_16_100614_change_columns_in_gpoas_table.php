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
        Schema::table('gpoas', function (Blueprint $table) {
/*
            $table->dropUnique(['active']);
            $table->dropColumn('active');
            $table->tinyInteger('active')
                ->storedAs('if(closed_at is null, 1, null)');
*/
            $table->unique('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gpoas', function (Blueprint $table) {
            //
        });
    }
};
