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
        Schema::table('gpoa_activities', function (Blueprint $table) {
            $table->timestamp('president_approved_at')->nullable()->change();
            $table->timestamp('adviser_approved_at')->nullable()->change();
            $table->timestamp('rejected_at')->nullable()->change();
            $table->timestamp('officers_submitted_at')->nullable();
            $table->timestamp('president_submitted_at')->nullable();
            $table->timestamp('president_returned_at')->nullable();
            $table->timestamp('adviser_returned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gpoa_activities', function (Blueprint $table) {
            $table->dropColumn('officers_submitted_at');
            $table->dropColumn('president_submitted_at');
            $table->dropColumn('president_returned_at');
            $table->dropColumn('adviser_returned_at');
        });
    }
};
