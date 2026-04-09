<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            // Force drop claims_old if it exists
            if (Schema::hasTable('claims_old')) {
                DB::statement('DROP TABLE IF EXISTS claims_old');
            }

            // Verify chat_messages table exists and has proper structure
            if (!Schema::hasTable('chat_messages')) {
                Schema::create('chat_messages', function ($table) {
                    $table->id();
                    $table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
                    $table->foreignId('claim_id')->constrained('claims')->cascadeOnDelete();
                    $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                    $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
                    $table->text('message');
                    $table->timestamp('read_at')->nullable();
                    $table->timestamps();
                    $table->index(['claim_id', 'sender_id', 'receiver_id']);
                });
            }

            // Verify chat_conversations table
            if (!Schema::hasTable('chat_conversations')) {
                Schema::create('chat_conversations', function ($table) {
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

        } catch (\Exception $e) {
            \Log::error('Migration error in fix_claims_old_reference', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // No rollback needed for this fix
    }
};
