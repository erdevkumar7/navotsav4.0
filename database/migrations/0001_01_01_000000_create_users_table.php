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
            $table->tinyInteger('user_type')->comment("1=Admin,2=event-organizer,3=finance-manager,4=end-user");
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable(); // citext for case-insensitive
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // nullable to allow pure social accounts
            $table->string('phone')->nullable()->unique();
            $table->date('dob')->nullable(); // for age verification
            $table->boolean('is_age_verified')->default(false);
            $table->string('avatar_url')->nullable();
            $table->boolean('is_mfa_enabled')->default(false);
            $table->string('preferred_language', 10)->default('en');
            $table->jsonb('notification_settings')->nullable(); // e.g. {"email":true,"sms":false,"push":true}
            $table->string('social_provider', 15)->nullable()->after('notification_settings');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
