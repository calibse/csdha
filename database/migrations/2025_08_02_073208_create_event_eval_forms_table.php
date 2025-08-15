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
        Schema::create('event_eval_form_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->text('introduction')->nullable();
            $table->string('overall_satisfaction')->nullable();
            $table->string('content_relevance')->nullable();
            $table->string('speaker_effectiveness')->nullable();
            $table->string('engagement_level')->nullable();
            $table->string('duration')->nullable();
            $table->string('topics_covered')->nullable();
            $table->string('suggestions_for_improvement')->nullable();
            $table->string('future_topics')->nullable();
            $table->string('overall_experience')->nullable();
            $table->string('additional_comments')->nullable();
            $table->text('acknowledgement')->nullable();
            $table->boolean('default')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_eval_forms');
    }
};
