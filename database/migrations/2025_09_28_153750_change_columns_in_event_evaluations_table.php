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
            $table->boolean('feature_topics_covered')->default(false)
                ->change();
            $table->boolean('feature_suggestions_for_improvement')
                ->default(false)->change();
            $table->boolean('feature_future_topics')->default(false)->change();
            $table->boolean('feature_overall_experience')->default(false)
                ->change();
            $table->boolean('feature_additional_comments')->default(false)
                ->change();
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
