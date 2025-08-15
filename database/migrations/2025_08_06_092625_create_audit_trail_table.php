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
        Schema::create('audit_trail', function (Blueprint $table) {
            $table->id();
            $table->string('action', length: 10);
            $table->string('table_name', length: 100);
            $table->json('column_names')->nullable();
            $table->unsignedBigInteger('primary_key');
            $table->ulid('request_id')->nullable();
            $table->ipAddress('request_ip')->nullable();
            $table->text('request_url')->nullable();
            $table->string('request_method', length: 10)->nullable();
            $table->timestamp('request_time')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id', length: 255)->nullable();
            $table->timestamps();
        });
        DB::statement("
            alter table audit_trail
            add constraint chk_action
            check (action in ('insert', 'update', 'delete'))
        ");
        DB::statement("
            alter table audit_trail
            add constraint chk_request_method
            check (request_method in ('get', 'post', 'put', 'patch', 
                'delete', 'options'))
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trail');
    }
};
