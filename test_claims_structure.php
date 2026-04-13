<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Claims Table Structure Test ===\n\n";

// Check if claims table exists
if (Schema::hasTable('claims')) {
    echo "✓ Claims table exists\n\n";
    
    // Get all columns
    $columns = DB::select('PRAGMA table_info(claims)');
    echo "Columns (" . count($columns) . " found):\n";
    foreach ($columns as $col) {
        echo "  - {$col->name} ({$col->type})" . ($col->notnull ? " NOT NULL" : "") . "\n";
    }
    
    // Check for claims_old table (should not exist)
    echo "\n";
    if (Schema::hasTable('claims_old')) {
        echo "✗ WARNING: claims_old table still exists (should be cleaned up)\n";
    } else {
        echo "✓ claims_old table successfully removed\n";
    }
    
    // Count records
    $count = DB::table('claims')->count();
    echo "\nDatabase records: {$count} claim(s) exist\n";
    
    // Check foreign key constraints
    echo "\nForeign key constraints:\n";
    $fks = DB::select("PRAGMA foreign_key_list(claims)");
    if (empty($fks)) {
        echo "  (No foreign key constraints found - this may indicate an issue)\n";
    } else {
        foreach ($fks as $fk) {
            echo "  - {$fk->from} -> {$fk->table}.{$fk->to}\n";
        }
    }
    
} else {
    echo "✗ Claims table does not exist!\n";
}

echo "\n=== Test Complete ===\n";
