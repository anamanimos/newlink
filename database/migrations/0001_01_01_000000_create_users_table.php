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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('type')->default(0); // 0 = user, 1 = admin
            $table->tinyInteger('status')->default(1); // 1 = active, 0 = inactive
            $table->text('billing')->nullable();
            $table->string('api_key', 32)->nullable();
            $table->string('token_code', 32)->nullable();
            $table->string('twofa_secret', 16)->nullable();
            $table->string('anti_phishing_code', 8)->nullable();
            $table->string('one_time_login_code', 32)->nullable();
            $table->string('plan_id', 16)->default('free');
            $table->datetime('plan_expiration_date')->nullable();
            $table->text('plan_settings')->nullable();
            $table->tinyInteger('plan_trial_done')->default(0);
            $table->tinyInteger('plan_expiry_reminder')->default(0);
            $table->string('payment_subscription_id', 64)->nullable();
            $table->string('payment_processor', 16)->nullable();
            $table->double('payment_total_amount')->nullable();
            $table->string('payment_currency', 4)->nullable();
            $table->string('referral_key', 32)->nullable();
            $table->string('referred_by', 32)->nullable();
            $table->tinyInteger('referred_by_has_converted')->default(0);
            $table->string('language', 32)->default('english');
            $table->string('currency', 4)->nullable();
            $table->string('timezone', 32)->default('Asia/Jakarta');
            $table->text('preferences')->nullable();
            $table->text('extra')->nullable();
            $table->datetime('last_activity')->nullable();
            $table->integer('total_logins')->default(0);
            $table->string('source', 32)->default('direct');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
