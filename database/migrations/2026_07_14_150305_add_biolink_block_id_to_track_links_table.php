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
        Schema::table('track_links', function (Blueprint $table) {
            $table->foreignId('biolink_block_id')->nullable()->after('link_id')->constrained('biolink_blocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('track_links', function (Blueprint $table) {
            $table->dropForeign(['biolink_block_id']);
            $table->dropColumn('biolink_block_id');
        });
    }
};
