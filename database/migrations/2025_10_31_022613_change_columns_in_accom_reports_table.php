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
        Schema::table('accom_reports', function (Blueprint $table) {
            $table->string('filepath')->nullable();
        });
        DB::statement("
            alter table accom_reports
            drop constraint chk_status
        ");
        DB::statement("
            alter table accom_reports
            add constraint chk_status
            check (status in ('draft', 'pending', 'returned', 'approved'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accom_reports', function (Blueprint $table) {
            //
        });
    }
};
