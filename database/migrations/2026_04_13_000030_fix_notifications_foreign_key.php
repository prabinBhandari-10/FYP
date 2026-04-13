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
            // Drop and recreate notifications table with correct foreign key
            Schema::dropIfExists('notifications_temp');
            
            // Create temporary table
            DB::statement('
                CREATE TABLE notifications_temp (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    type VARCHAR NOT NULL,
                    title VARCHAR NOT NULL,
                    message TEXT NOT NULL,
                    related_report_id INTEGER,
                    related_claim_id INTEGER,
                    is_read TINYINT NOT NULL DEFAULT 0,
                    is_email_sent TINYINT NOT NULL DEFAULT 0,
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (related_report_id) REFERENCES reports(id) ON DELETE SET NULL,
                    FOREIGN KEY (related_claim_id) REFERENCES claims(id) ON DELETE SET NULL
                )
            ');
            
            // Copy data from old table
            DB::statement('INSERT INTO notifications_temp SELECT * FROM notifications');
            
            // Drop old table
            DB::statement('DROP TABLE notifications');
            
            // Rename temp table
            DB::statement('ALTER TABLE notifications_temp RENAME TO notifications');
            
            // Recreate indexes
            DB::statement('CREATE INDEX notifications_user_id_index ON notifications(user_id)');
            DB::statement('CREATE INDEX notifications_created_at_index ON notifications(created_at)');
            
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // Rollback not implemented - this is a data structure fix
    }
};
