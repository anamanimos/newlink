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
        if (!Schema::hasTable('plans')) {
            Schema::create('plans', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('monthly_price', 10, 2)->default(0);
                $table->decimal('annual_price', 10, 2)->default(0);
                $table->decimal('lifetime_price', 10, 2)->default(0);
                $table->string('currency', 10)->default('USD');
                $table->string('badge', 64)->nullable();
                $table->string('color', 32)->default('#3e97ff');
                $table->json('settings')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('is_enabled')->default(1);
                $table->timestamps();
            });

            // Seed initial plans
            DB::table('plans')->insert([
                [
                    'name' => 'Free Plan',
                    'slug' => 'free',
                    'description' => 'Paket default untuk pengguna baru & pemula',
                    'monthly_price' => 0.00,
                    'annual_price' => 0.00,
                    'lifetime_price' => 0.00,
                    'currency' => 'USD',
                    'badge' => 'Default',
                    'color' => '#7239ea',
                    'settings' => json_encode([
                        'biolinks_limit' => 15,
                        'links_limit' => 50,
                        'projects_limit' => 3,
                        'domains_limit' => 0,
                        'pixels_limit' => 0,
                        'custom_branding' => false,
                        'statistics' => 'basic',
                        'verified_badge' => false,
                        'dofollow_links' => false
                    ]),
                    'order' => 1,
                    'is_enabled' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Custom Plan',
                    'slug' => 'custom',
                    'description' => 'Paket fleksibel untuk tim & kampanye terdedikasi',
                    'monthly_price' => 15.00,
                    'annual_price' => 150.00,
                    'lifetime_price' => 299.00,
                    'currency' => 'USD',
                    'badge' => 'Custom',
                    'color' => '#f1416c',
                    'settings' => json_encode([
                        'biolinks_limit' => 50,
                        'links_limit' => 200,
                        'projects_limit' => 10,
                        'domains_limit' => 3,
                        'pixels_limit' => 5,
                        'custom_branding' => true,
                        'statistics' => 'advanced',
                        'verified_badge' => true,
                        'dofollow_links' => true
                    ]),
                    'order' => 2,
                    'is_enabled' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Pro Plan',
                    'slug' => 'pro',
                    'description' => 'Paket tak terbatas untuk profesional & agensi bisnis',
                    'monthly_price' => 29.00,
                    'annual_price' => 290.00,
                    'lifetime_price' => 499.00,
                    'currency' => 'USD',
                    'badge' => 'Popular',
                    'color' => '#3e97ff',
                    'settings' => json_encode([
                        'biolinks_limit' => -1, // Unlimited
                        'links_limit' => -1,
                        'projects_limit' => -1,
                        'domains_limit' => 10,
                        'pixels_limit' => 20,
                        'custom_branding' => true,
                        'statistics' => 'advanced',
                        'verified_badge' => true,
                        'dofollow_links' => true
                    ]),
                    'order' => 3,
                    'is_enabled' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
