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
        // For SQLite compatibility, we need to recreate the column with new enum values
        Schema::table('notifications', function (Blueprint $table) {
            // SQLite doesn't support modifying enum columns directly, so we alter the constraint
            // This modifies the type enum to include new notification types
            $table->enum('type', [
                'similar_item',
                'claim_received',
                'claim_approved',
                'claim_rejected',
                'report_comment',
                'new_contact_message',
                'new_user_registered',
                'new_report'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('type', [
                'similar_item',
                'claim_received',
                'claim_approved',
                'claim_rejected',
                'report_comment'
            ])->change();
        });
    }
};
