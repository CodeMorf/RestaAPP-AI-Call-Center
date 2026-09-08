<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_call_center_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->unique();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->boolean('enabled')->default(false);
            $table->string('provider')->default('elevenlabs');
            $table->string('elevenlabs_agent_id')->nullable();
            $table->text('elevenlabs_api_key')->nullable();
            $table->json('permissions')->nullable();
            $table->string('human_transfer_number')->nullable();
            $table->decimal('monthly_price', 12, 2)->nullable();
            $table->string('currency_code', 8)->default('DOP');
            $table->timestamps();
        });

        Schema::create('ai_call_center_devices', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('sim_operator')->nullable();
            $table->string('sim_country', 8)->nullable();
            $table->string('device_model')->nullable();
            $table->string('android_version')->nullable();
            $table->string('app_version')->nullable();
            $table->string('status')->default('paired')->index();
            $table->string('token_hash', 64)->unique();
            $table->json('capabilities')->nullable();
            $table->unsignedTinyInteger('battery_level')->nullable();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_call_center_pairings', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('token_hash', 64)->unique();
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable();
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('ai_call_center_calls', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->string('direction', 16)->index();
            $table->string('remote_number')->nullable()->index();
            $table->string('local_number')->nullable();
            $table->string('status')->default('started')->index();
            $table->string('elevenlabs_conversation_id')->nullable()->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_call_center_sms', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->string('direction', 16)->index();
            $table->string('phone_number')->index();
            $table->text('message');
            $table->string('purpose')->nullable();
            $table->string('status')->default('queued')->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('failure_message')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_call_center_cart_tokens', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('token_hash', 64)->unique();
            $table->json('snapshot');
            $table->decimal('total', 14, 2);
            $table->string('currency_code', 8);
            $table->timestamp('expires_at')->index();
            $table->timestamp('consumed_at')->nullable();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('ai_call_center_action_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('call_id')->nullable()->index();
            $table->string('tool_name')->index();
            $table->string('status')->index();
            $table->string('conversation_id')->nullable()->index();
            $table->json('safe_context')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_call_center_action_logs');
        Schema::dropIfExists('ai_call_center_cart_tokens');
        Schema::dropIfExists('ai_call_center_sms');
        Schema::dropIfExists('ai_call_center_calls');
        Schema::dropIfExists('ai_call_center_pairings');
        Schema::dropIfExists('ai_call_center_devices');
        Schema::dropIfExists('ai_call_center_settings');
    }
};
