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
        Schema::create('event_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('event_attendee_id')->constrained();
            $table->tinyInteger('overall_satisfaction');
            $table->tinyInteger('content_relevance');
            $table->tinyInteger('speaker_effectiveness');
            $table->tinyInteger('engagement_level');
            $table->tinyInteger('duration');
            $table->text('topics_covered');
            $table->text('suggestions_for_improvement');
            $table->text('future_topics');
            $table->text('overall_experience');
            $table->text('additional_comments')->nullable();
            $table->boolean('selected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_evaluations');
    }
};
