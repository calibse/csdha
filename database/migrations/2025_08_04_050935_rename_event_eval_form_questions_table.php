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
        Schema::rename('event_eval_form_questions', 'event_eval_forms');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
