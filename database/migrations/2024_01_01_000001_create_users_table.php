<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Role & approval
            $table->enum('role', ['admin', 'agent', 'client'])->default('client');
            $table->boolean('is_approved')->default(false)->comment('Agents must be approved by admin');

            // Profile fields
            $table->string('phone', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();

            // Agent-specific
            $table->string('agency_name')->nullable();
            $table->string('license_number')->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->index('role');
            $table->index(['role', 'is_approved']);
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

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
