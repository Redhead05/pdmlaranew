CCCCCC<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_guests', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->index();
            $table->string('phone', 20)->index();
            $table->string('session_token', 64)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('chat_guests')->cascadeOnDelete();
            $table->string('status')->default('open')->index(); // open|closed|blocked
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_message_at')->nullable()->index();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
            $table->string('sender_type')->index(); // guest|admin
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->timestamp('sent_at')->useCurrent()->index();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('read_at')->nullable()->index(); // read receipts (per-message, used for guest->admin)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('chat_guests');
    }
};

