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
        Schema::table('domains', function (Blueprint $table) {
            if (!Schema::hasColumn('domains', 'ssl_status')) {
                $table->string('ssl_status', 32)->default('none')->after('is_enabled'); // none, pending, active, failed
            }
            if (!Schema::hasColumn('domains', 'dns_status')) {
                $table->string('dns_status', 32)->default('pending')->after('ssl_status'); // pending, verified, failed
            }
            if (!Schema::hasColumn('domains', 'last_dns_check_at')) {
                $table->timestamp('last_dns_check_at')->nullable()->after('dns_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['ssl_status', 'dns_status', 'last_dns_check_at']);
        });
    }
};
