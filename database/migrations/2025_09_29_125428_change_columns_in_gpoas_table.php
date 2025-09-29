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
            $table->string('report_filepath')->nullable();
            $table->string('accom_report_filepath')->nullable();
            $table->timestamp('closed_at')->nullable();
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
