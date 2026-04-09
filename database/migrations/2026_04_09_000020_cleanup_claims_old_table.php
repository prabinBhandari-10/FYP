<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Clean up any remaining claims_old table that might have been left behind
        if ($this->tableExists('claims_old')) {
            Schema::disableForeignKeyConstraints();
            DB::statement('DROP TABLE IF EXISTS claims_old');
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // Nothing to rollback
    }

    private function tableExists($table): bool
    {
        return Schema::hasTable($table);
    }
};
