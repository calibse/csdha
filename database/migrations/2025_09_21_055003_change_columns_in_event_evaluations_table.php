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
        Schema::table('event_evaluations', function (Blueprint $table) {
            $table->dropForeign(['event_attendee_id']);
            $table->dropColumn('event_attendee_id');
            $table->dropColumn('selected');
            $table->boolean('feature_topics_covered');
            $table->boolean('feature_suggestions_for_improvement');
            $table->boolean('feature_future_topics');
            $table->boolean('feature_overall_experience');
            $table->boolean('feature_additional_comments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_evaluations', function (Blueprint $table) {
            //
        });
    }
};
