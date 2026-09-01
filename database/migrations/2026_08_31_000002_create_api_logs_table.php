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
        if (!Schema::hasTable('api_logs')) {
            Schema::create('api_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('api_key', 64)->nullable()->index();
                $table->string('endpoint', 255);
                $table->string('method', 10)->default('GET');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->integer('status_code')->default(200);
                $table->float('response_time_ms')->default(0);
                $table->text('request_payload')->nullable();
                $table->text('response_summary')->nullable();
                $table->timestamp('created_at')->useCurrent()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
