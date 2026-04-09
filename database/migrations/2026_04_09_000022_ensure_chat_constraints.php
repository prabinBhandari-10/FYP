<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Ensure chat_conversations table has all proper constraints and indexes
        if (Schema::hasTable('chat_conversations')) {
            // Check if the unique constraint exists, if not, recreate the table
            $hasConstraint = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name='chat_conversations' AND name LIKE '%claim_id%'");
            
            if (empty($hasConstraint)) {
                // Drop and recreate with proper constraints
                Schema::dropIfExists('chat_conversations');
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
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // No rollback needed
    }
};
