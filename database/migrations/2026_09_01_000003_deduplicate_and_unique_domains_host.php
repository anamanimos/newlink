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
        // 1. Clean up existing duplicate domains
        $duplicates = DB::table('domains')
            ->select('host', DB::raw('COUNT(*) as total'))
            ->groupBy('host')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $records = DB::table('domains')
                ->where('host', $dup->host)
                ->orderBy('id', 'ASC')
                ->get();

            if ($records->count() > 1) {
                $master = $records->first();
                $duplicateIds = $records->slice(1)->pluck('id')->toArray();

                // Reassign all links from duplicate domain IDs to master ID
                DB::table('links')
                    ->whereIn('domain_id', $duplicateIds)
                    ->update(['domain_id' => $master->id]);

                // Delete duplicate domain rows
                DB::table('domains')
                    ->whereIn('id', $duplicateIds)
                    ->delete();
            }
        }

        // 2. Add unique constraint on host column
        Schema::table('domains', function (Blueprint $table) {
            try {
                $table->unique('host');
            } catch (\Exception $e) {
                // Ignore if unique already exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropUnique(['host']);
        });
    }
};
