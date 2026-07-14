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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->unsignedBigInteger('domain_id')->default(0);
            $table->unsignedBigInteger('biolink_theme_id')->nullable();
            $table->unsignedBigInteger('biolink_id')->nullable();
            $table->text('pixels_ids')->nullable();
            $table->string('type', 32)->default('');
            $table->string('url', 256)->default('');
            $table->string('location_url', 2048)->nullable();
            $table->integer('clicks')->default(0);
            $table->text('settings')->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('directory_is_enabled')->default(1);
            $table->tinyInteger('is_enabled')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
