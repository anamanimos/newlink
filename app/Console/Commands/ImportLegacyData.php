<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Domain;
use App\Models\Pixel;
use App\Models\Link;
use App\Models\BiolinkBlock;

class ImportLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-legacy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import legacy data from the AltumCode Biolink database to the new Laravel schema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting legacy database import...');

        // 1. Check if legacy connection is configured and reachable
        try {
            $legacyDb = DB::connection('legacy');
            $legacyDb->getPdo();
            $this->info('Successfully connected to the legacy database.');
        } catch (\Exception $e) {
            $this->error('Could not connect to the legacy database. Please check your .env file.');
            $this->error($e->getMessage());
            return Command::FAILURE;
        }

        // 2. Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 3. Import Users
        $this->importUsers($legacyDb);

        // 4. Import Projects
        $this->importProjects($legacyDb);

        // 5. Import Domains
        $this->importDomains($legacyDb);

        // 6. Import Pixels
        $this->importPixels($legacyDb);

        // 7. Import Links
        $this->importLinks($legacyDb);

        // 8. Import Biolink Blocks
        $this->importBiolinkBlocks($legacyDb);

        // 9. Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Data import process completed successfully!');
        return Command::SUCCESS;
    }

    private function importUsers($legacyDb)
    {
        $this->info('Importing users...');
        User::truncate();

        $users = $legacyDb->table('users')->get();
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();

        foreach ($users as $user) {
            User::create([
                'id' => $user->user_id,
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
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Users imported: ' . count($users));
    }

    private function importProjects($legacyDb)
    {
        $this->info('Importing projects...');
        Project::truncate();

        $projects = $legacyDb->table('projects')->get();
        $bar = $this->output->createProgressBar(count($projects));
        $bar->start();

        foreach ($projects as $project) {
            Project::create([
                'id' => $project->project_id,
                'user_id' => $project->user_id,
                'name' => $project->name,
                'color' => $project->color ?: '#000000',
                'created_at' => $project->datetime ?: now(),
                'updated_at' => $project->last_datetime ?: now(),
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Projects imported: ' . count($projects));
    }

    private function importDomains($legacyDb)
    {
        $this->info('Importing domains...');
        Domain::truncate();

        $domains = $legacyDb->table('domains')->get();
        $bar = $this->output->createProgressBar(count($domains));
        $bar->start();

        foreach ($domains as $domain) {
            Domain::create([
                'id' => $domain->domain_id,
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
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Domains imported: ' . count($domains));
    }

    private function importPixels($legacyDb)
    {
        $this->info('Importing pixels...');
        Pixel::truncate();

        $pixels = $legacyDb->table('pixels')->get();
        $bar = $this->output->createProgressBar(count($pixels));
        $bar->start();

        foreach ($pixels as $pixel) {
            Pixel::create([
                'id' => $pixel->pixel_id,
                'user_id' => $pixel->user_id,
                'type' => $pixel->type,
                'name' => $pixel->name,
                'pixel' => $pixel->pixel,
                'created_at' => $pixel->datetime ?: now(),
                'updated_at' => $pixel->last_datetime ?: now(),
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Pixels imported: ' . count($pixels));
    }

    private function importLinks($legacyDb)
    {
        $this->info('Importing links...');
        Link::truncate();

        $links = $legacyDb->table('links')->get();
        $bar = $this->output->createProgressBar(count($links));
        $bar->start();

        foreach ($links as $link) {
            Link::create([
                'id' => $link->link_id,
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
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Links imported: ' . count($links));
    }

    private function importBiolinkBlocks($legacyDb)
    {
        $this->info('Importing biolink blocks...');
        BiolinkBlock::truncate();

        $blocks = $legacyDb->table('biolinks_blocks')->get();
        $bar = $this->output->createProgressBar(count($blocks));
        $bar->start();

        foreach ($blocks as $block) {
            BiolinkBlock::create([
                'id' => $block->biolink_block_id,
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
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Biolink blocks imported: ' . count($blocks));
    }
}
