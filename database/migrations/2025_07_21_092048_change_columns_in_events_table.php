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
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['gspoa_event_id']);
            $table->dropColumn([
                'title',
                'participants',
                'cover_photo_filepath',
                'user_id',
                'type_of_activity',
                'objective',
                'gspoa_event_id'
            ]);
            $table->foreignId('gpoa_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
