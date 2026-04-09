<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->boolean('payment_required')->default(false)->after('held_at');
            $table->unsignedInteger('payment_amount')->nullable()->after('payment_required');
            $table->string('payment_reason')->nullable()->after('payment_amount');
            $table->string('payment_status')->nullable()->after('payment_reason');
            $table->string('payment_pidx')->nullable()->after('payment_status');
            $table->timestamp('payment_completed_at')->nullable()->after('payment_pidx');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE claims MODIFY status ENUM('pending','awaiting_payment','under_verification','approved','rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE claims MODIFY status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn([
                'payment_required',
                'payment_amount',
                'payment_reason',
                'payment_status',
                'payment_pidx',
                'payment_completed_at',
            ]);
        });
    }
};
