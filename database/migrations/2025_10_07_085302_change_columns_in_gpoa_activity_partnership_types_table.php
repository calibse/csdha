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
        Schema::table('gpoa_activity_partnership_types', function (Blueprint $table) {
            $table->softDeletes();
            $table->tinyInteger('unarchived')
                ->storedAs('if(deleted_at is null, 1, null)');
            $table->unique(['name', 'unarchived']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gpoa_activity_partnership_types', function (Blueprint $table) {
            //
        });
    }
};
