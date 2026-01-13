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
        Schema::create('audit_trail_data', function (Blueprint $table) {
            $table->string('action', length: 10)->nullable();
            $table->string('table_name', length: 100)->nullable();
            $table->longText('column_names')->nullable();
            $table->bigInteger('primary_key')->nullable();
            $table->char('request_id', length: 26)->nullable();
            $table->string('request_ip', 45)->nullable();
            $table->text('request_url')->nullable();
            $table->string('request_method', length: 10)->nullable();
            $table->timestamp('request_time')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trail_data');
    }
};
