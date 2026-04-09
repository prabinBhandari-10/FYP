<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set email_verified_at for all users who don't have it yet
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: set email_verified_at to null
        // Commented out to be safe - adjust as needed
        // DB::table('users')->update(['email_verified_at' => null]);
    }
};
