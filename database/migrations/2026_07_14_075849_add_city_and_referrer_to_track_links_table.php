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
            $table->string('city_name', 128)->nullable()->after('country_code');
            $table->string('referrer_host', 256)->nullable()->after('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('track_links', function (Blueprint $table) {
            $table->dropColumn(['city_name', 'referrer_host']);
        });
    }
};
