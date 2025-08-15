<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gpoas', function (Blueprint $table) {
            $table->dropColumn('closed');
            $table->boolean('active')->unique()->nullable();
        });

        DB::statement('
            alter table gpoas
            add constraint chk_active
            check (active = 1 or active is null)
        ');
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
