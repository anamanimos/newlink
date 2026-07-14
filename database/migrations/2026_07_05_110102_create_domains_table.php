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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id')->nullable(); // circular ref, defined as nullable field
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('scheme', 8)->default('https://');
            $table->string('host', 256);
            $table->string('custom_index_url', 256)->nullable();
            $table->string('custom_not_found_url', 256)->nullable();
            $table->tinyInteger('type')->default(1); // 0 = custom domain, 1 = system domain
            $table->tinyInteger('is_enabled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
