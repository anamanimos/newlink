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
        Schema::create('whatsapp_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biolink_block_id')->constrained('biolink_blocks')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('whatsapp_number_used')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_leads');
    }
};
