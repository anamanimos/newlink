<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Test connection to legacy database
     */
    public function checkConnection()
    {
        try {
            $legacyDb = DB::connection('legacy');
            $legacyDb->getPdo();

            $totalUsers = $legacyDb->table('users')->count();
            $totalLinks = $legacyDb->table('links')->count();

            return response()->json([
                'success' => true,
                'message' => 'Terhubung ke database aplikasi lama.',
                'stats' => [
                    'users' => $totalUsers,
                    'links' => $totalLinks
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke database aplikasi lama: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process single step of incremental sync via AJAX
     */
    public function processStep(Request $request)
    {
        $step = $request->input('step', 'users');

        try {
            $legacyDb = DB::connection('legacy');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            switch ($step) {
                case 'users':
                    $res = $this->syncUsers($legacyDb);
                    return response()->json([
                        'success' => true,
                        'current_step' => 'users',
                        'next_step' => 'projects',
                        'progress' => 15,
                        'message' => "Users: {$res['created']} ditambah, {$res['updated']} diperbarui."
                    ]);

                case 'projects':
                    $res = $this->syncProjects($legacyDb);
                    return response()->json([
                        'success' => true,
                        'current_step' => 'projects',
                        'next_step' => 'domains',
                        'progress' => 30,
                        'message' => "Projects: {$res['created']} ditambah, {$res['updated']} diperbarui."
                    ]);

                case 'domains':
                    $res = $this->syncDomains($legacyDb);
                    return response()->json([
                        'success' => true,
                        'current_step' => 'domains',
                        'next_step' => 'pixels',
                        'progress' => 45,
                        'message' => "Domains: {$res['created']} ditambah, {$res['updated']} diperbarui."
                    ]);

                case 'pixels':
                    $res = $this->syncPixels($legacyDb);
                    return response()->json([
                        'success' => true,
                        'current_step' => 'pixels',
                        'next_step' => 'links',
                        'progress' => 60,
                        'message' => "Pixels: {$res['created']} ditambah, {$res['updated']} diperbarui."
                    ]);

                case 'links':
                    $res = $this->syncLinks($legacyDb);
                    return response()->json([
                        'success' => true,
                        'current_step' => 'links',
                        'next_step' => 'biolink_blocks',
                        'progress' => 75,
                        'message' => "Links: {$res['created']} ditambah, {$res['updated']} diperbarui."
                    ]);

                case 'biolink_blocks':
                    $res = $this->syncBiolinkBlocks($legacyDb);
                    return response()->json([
                        'success' => true,
                        'current_step' => 'biolink_blocks',
                        'next_step' => 'track_links',
                        'progress' => 90,
                        'message' => "Biolink Blocks: {$res['created']} ditambah, {$res['updated']} diperbarui."
                    ]);

                case 'track_links':
                    $res = $this->syncTrackLinks($legacyDb);
                    return response()->json([
                        'success' => true,
                        'current_step' => 'track_links',
                        'next_step' => 'finish',
                        'progress' => 98,
                        'message' => "Track Links (Klik): {$res['synced']} log klik baru disinkronkan."
                    ]);

                case 'finish':
                    $this->alignAutoIncrements();
                    return response()->json([
                        'success' => true,
                        'current_step' => 'finish',
                        'next_step' => null,
                        'progress' => 100,
                        'message' => 'Sinkronisasi selesai 100% tanpa duplikasi!'
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Unknown sync step.'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada step ' . $step . ': ' . $e->getMessage()
            ], 500);
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function syncUsers($legacyDb)
    {
        $users = $legacyDb->table('users')->get();
        $updated = 0;
        $created = 0;

        foreach ($users as $user) {
            $exists = DB::table('users')->where('id', $user->user_id)->exists();

            DB::table('users')->updateOrInsert(
                ['id' => $user->user_id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->status == 1 ? now() : null,
                    'password' => $user->password,
                    'type' => $user->type,
                    'status' => $user->status,
                    'billing' => $user->billing,
                    'api_key' => $user->api_key,
                    'token_code' => $user->token_code,
                    'twofa_secret' => $user->twofa_secret,
                    'anti_phishing_code' => $user->anti_phishing_code,
                    'one_time_login_code' => $user->one_time_login_code,
                    'plan_id' => $user->plan_id ?: 'free',
                    'plan_expiration_date' => $user->plan_expiration_date,
                    'plan_settings' => $user->plan_settings,
                    'plan_trial_done' => $user->plan_trial_done,
                    'plan_expiry_reminder' => $user->plan_expiry_reminder,
                    'payment_subscription_id' => $user->payment_subscription_id,
                    'payment_processor' => $user->payment_processor,
                    'payment_total_amount' => $user->payment_total_amount,
                    'payment_currency' => $user->payment_currency,
                    'referral_key' => $user->referral_key,
                    'referred_by' => $user->referred_by,
                    'referred_by_has_converted' => $user->referred_by_has_converted,
                    'language' => $user->language ?: 'english',
                    'currency' => $user->currency,
                    'timezone' => $user->timezone ?: 'Asia/Jakarta',
                    'preferences' => $user->preferences,
                    'extra' => $user->extra,
                    'last_activity' => $user->last_activity,
                    'total_logins' => $user->total_logins ?: 0,
                    'source' => $user->source ?: 'direct',
                    'created_at' => $user->datetime ?: now(),
                    'updated_at' => $user->last_activity ?: now(),
                ]
            );

            if ($exists) $updated++; else $created++;
        }

        return ['created' => $created, 'updated' => $updated];
    }

    private function syncProjects($legacyDb)
    {
        $projects = $legacyDb->table('projects')->get();
        $updated = 0;
        $created = 0;

        foreach ($projects as $project) {
            $exists = DB::table('projects')->where('id', $project->project_id)->exists();

            DB::table('projects')->updateOrInsert(
                ['id' => $project->project_id],
                [
                    'user_id' => $project->user_id,
                    'name' => $project->name,
                    'color' => $project->color ?: '#000000',
                    'created_at' => $project->datetime ?: now(),
                    'updated_at' => $project->last_datetime ?: now(),
                ]
            );

            if ($exists) $updated++; else $created++;
        }

        return ['created' => $created, 'updated' => $updated];
    }

    private function syncDomains($legacyDb)
    {
        $domains = $legacyDb->table('domains')->get();
        $updated = 0;
        $created = 0;

        foreach ($domains as $domain) {
            $exists = DB::table('domains')->where('id', $domain->domain_id)->exists();

            DB::table('domains')->updateOrInsert(
                ['id' => $domain->domain_id],
                [
                    'link_id' => $domain->link_id,
                    'user_id' => $domain->user_id,
                    'scheme' => $domain->scheme ?: 'https://',
                    'host' => $domain->host,
                    'custom_index_url' => $domain->custom_index_url,
                    'custom_not_found_url' => $domain->custom_not_found_url,
                    'type' => $domain->type,
                    'is_enabled' => $domain->is_enabled,
                    'created_at' => $domain->datetime ?: now(),
                    'updated_at' => $domain->last_datetime ?: now(),
                ]
            );

            if ($exists) $updated++; else $created++;
        }

        return ['created' => $created, 'updated' => $updated];
    }

    private function syncPixels($legacyDb)
    {
        $pixels = $legacyDb->table('pixels')->get();
        $updated = 0;
        $created = 0;

        foreach ($pixels as $pixel) {
            $exists = DB::table('pixels')->where('id', $pixel->pixel_id)->exists();

            DB::table('pixels')->updateOrInsert(
                ['id' => $pixel->pixel_id],
                [
                    'user_id' => $pixel->user_id,
                    'type' => $pixel->type,
                    'name' => $pixel->name,
                    'pixel' => $pixel->pixel,
                    'created_at' => $pixel->datetime ?: now(),
                    'updated_at' => $pixel->last_datetime ?: now(),
                ]
            );

            if ($exists) $updated++; else $created++;
        }

        return ['created' => $created, 'updated' => $updated];
    }

    private function syncLinks($legacyDb)
    {
        $links = $legacyDb->table('links')->get();
        $updated = 0;
        $created = 0;

        foreach ($links as $link) {
            $exists = DB::table('links')->where('id', $link->link_id)->exists();

            DB::table('links')->updateOrInsert(
                ['id' => $link->link_id],
                [
                    'user_id' => $link->user_id,
                    'project_id' => $link->project_id,
                    'domain_id' => $link->domain_id ?: 0,
                    'biolink_theme_id' => $link->biolink_theme_id,
                    'biolink_id' => $link->biolink_id,
                    'pixels_ids' => $link->pixels_ids,
                    'type' => $link->type ?: '',
                    'url' => $link->url ?: '',
                    'location_url' => $link->location_url,
                    'clicks' => $link->clicks ?: 0,
                    'settings' => $link->settings,
                    'start_date' => $link->start_date,
                    'end_date' => $link->end_date,
                    'is_verified' => $link->is_verified ?: 0,
                    'directory_is_enabled' => $link->directory_is_enabled ?: 1,
                    'is_enabled' => $link->is_enabled ?: 1,
                    'created_at' => $link->datetime ?: now(),
                    'updated_at' => $link->last_datetime ?: now(),
                ]
            );

            if ($exists) $updated++; else $created++;
        }

        return ['created' => $created, 'updated' => $updated];
    }

    private function syncBiolinkBlocks($legacyDb)
    {
        $blocks = $legacyDb->table('biolinks_blocks')->get();
        $updated = 0;
        $created = 0;

        foreach ($blocks as $block) {
            $exists = DB::table('biolink_blocks')->where('id', $block->biolink_block_id)->exists();

            DB::table('biolink_blocks')->updateOrInsert(
                ['id' => $block->biolink_block_id],
                [
                    'user_id' => $block->user_id,
                    'link_id' => $block->link_id,
                    'type' => $block->type ?: '',
                    'location_url' => $block->location_url,
                    'clicks' => $block->clicks ?: 0,
                    'settings' => $block->settings,
                    'order' => $block->order ?: 0,
                    'start_date' => $block->start_date,
                    'end_date' => $block->end_date,
                    'is_enabled' => $block->is_enabled ?: 1,
                    'created_at' => $block->datetime ?: now(),
                    'updated_at' => $block->last_datetime ?: now(),
                ]
            );

            if ($exists) $updated++; else $created++;
        }

        return ['created' => $created, 'updated' => $updated];
    }

    private function syncTrackLinks($legacyDb)
    {
        $maxCurrentId = DB::table('track_links')->max('id') ?? 0;
        $newClicks = $legacyDb->table('track_links')
            ->where('id', '>', $maxCurrentId)
            ->orderBy('id')
            ->limit(5000)
            ->get();

        $synced = 0;
        if ($newClicks->isNotEmpty()) {
            $insertData = [];
            foreach ($newClicks as $click) {
                $insertData[] = [
                    'id' => $click->id,
                    'link_id' => $click->link_id,
                    'user_id' => $click->user_id,
                    'ip' => null,
                    'country_code' => $click->country_code,
                    'city_name' => $click->city_name ?? null,
                    'os' => $click->os_name ?? $click->os ?? null,
                    'browser' => $click->browser_name ?? $click->browser ?? null,
                    'device_type' => $click->device_type,
                    'referrer_host' => $click->referrer_host ?? null,
                    'datetime' => $click->datetime ?: now(),
                ];
            }
            DB::table('track_links')->insertOrIgnore($insertData);
            $synced = count($insertData);
        }

        return ['synced' => $synced];
    }

    private function alignAutoIncrements()
    {
        $tables = ['users', 'projects', 'domains', 'pixels', 'links', 'biolink_blocks', 'track_links'];
        foreach ($tables as $table) {
            $maxId = DB::table($table)->max('id') ?? 0;
            $nextId = $maxId + 1;
            try {
                DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = {$nextId};");
            } catch (\Exception $e) {
                // Ignore
            }
        }
    }
}
