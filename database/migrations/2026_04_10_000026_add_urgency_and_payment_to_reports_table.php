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
        Schema::table('reports', function (Blueprint $table) {
            // Add urgency field (normal or urgent)
            $table->enum('urgency', ['normal', 'urgent'])->default('normal')->after('status');
            
            // Add payment tracking fields
            $table->string('payment_pidx')->nullable()->after('urgency')->comment('Khalti payment transaction ID');
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending')->after('payment_pidx')->comment('Payment status for urgent reports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['urgency', 'payment_pidx', 'payment_status']);
        });
    }
};
