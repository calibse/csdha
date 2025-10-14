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
            $table->dropForeign(['adviser_user_id']);
            $table->renameColumn('adviser_user_id', 'creator_user_id');
            $table->foreign('creator_user_id')->references('id')->on('users');
            $table->foreignId('closer_user_id')->nullable();
            $table->foreign('closer_user_id')->references('id')->on('users');
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
