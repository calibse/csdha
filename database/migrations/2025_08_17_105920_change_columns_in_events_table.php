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
        Schema::table('events', function (Blueprint $table) {
            $table->string('participant_type', length: 15)->nullable();
            $table->boolean('automatic_attendance');
            $table->boolean('accept_evaluation');
        });
        DB::statement("
            alter table events
            add constraint chk_participant_type
            check (participant_type in ('students', 'officers'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('participant_type');
            $table->dropColumn('automatic_attendance');
            $table->dropColumn('accept_evaluation');
        });
    }
};
