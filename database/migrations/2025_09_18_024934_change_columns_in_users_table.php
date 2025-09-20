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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropUnique(['position_id']);
            $table->foreign('position_id')->references('id')->on('positions');
            $table->dropUnique(['username']);
            $table->dropUnique(['google_id']);
            $table->tinyInteger('unarchived')
                ->storedAs('IF(deleted_at IS NULL, 1, NULL)');
            $table->unique(['position_id', 'unarchived']);
            $table->unique(['username', 'unarchived']);
            $table->unique(['google_id', 'unarchived']);
            $table->unique(['email', 'unarchived']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
