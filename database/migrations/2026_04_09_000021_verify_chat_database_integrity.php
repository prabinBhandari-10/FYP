<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure proper database state for chat functionality
        // This migration validates and fixes any remaining issues from previous migrations

        Schema::disableForeignKeyConstraints();

        // Verify and recreate chat_conversations if needed
        if (!Schema::hasTable('chat_conversations')) {
            Schema::create('chat_conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('claim_id')->unique()->constrained('claims')->cascadeOnDelete();
                $table->foreignId('finder_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('claimant_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();

                $table->index(['finder_id', 'claimant_id']);
            });
        }

        // Verify chat_messages table references are correct
        if (Schema::hasTable('chat_messages')) {
            // Ensure all conversation references exist
            $orphanedMessages = DB::table('chat_messages')
                ->whereNotIn('conversation_id', DB::table('chat_conversations')->pluck('id'))
                ->count();

            if ($orphanedMessages > 0) {
                DB::table('chat_messages')
                    ->whereNotIn('conversation_id', DB::table('chat_conversations')->pluck('id'))
                    ->delete();
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // No rollback needed
    }
};
