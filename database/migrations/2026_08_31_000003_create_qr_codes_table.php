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
        if (!Schema::hasTable('qr_codes')) {
            Schema::create('qr_codes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
                $table->string('name', 128);
                $table->string('type', 32)->default('url'); // url, text, email, phone, sms, whatsapp, wifi, vcard
                $table->text('content');
                $table->string('foreground_color', 32)->default('#000000');
                $table->string('background_color', 32)->default('#ffffff');
                $table->integer('size')->default(300);
                $table->integer('margin')->default(2);
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
