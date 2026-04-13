<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        Schema::disableForeignKeyConstraints();

        try {
            // Final safety cleanup for claims_old
            if ($this->tableExists('claims_old')) {
                DB::statement('DROP TABLE IF EXISTS claims_old');
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // Nothing to rollback
    }

    private function tableExists(string $table): bool
    {
        return DB::table('sqlite_master')
            ->where('type', 'table')
            ->where('name', $table)
            ->exists();
    }
};
