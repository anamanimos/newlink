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
        Schema::table('whatsapp_leads', function (Blueprint $table) {
            // Make biolink_block_id nullable
            $table->foreignId('biolink_block_id')->nullable()->change();
            
            // Add link_id foreign key referencing links table
            $table->foreignId('link_id')->nullable()->after('biolink_block_id')->constrained('links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_leads', function (Blueprint $table) {
            $table->dropForeign(['link_id']);
            $table->dropColumn('link_id');
            $table->foreignId('biolink_block_id')->nullable(false)->change();
        });
    }
};
