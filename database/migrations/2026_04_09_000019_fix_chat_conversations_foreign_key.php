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

        // Drop chat_conversations table if it exists with broken constraints
        if (Schema::hasTable('chat_conversations')) {
            Schema::dropIfExists('chat_conversations');
        }

        // Recreate chat_conversations table with proper foreign key constraints
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

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('chat_conversations');
        Schema::enableForeignKeyConstraints();
    }
};
