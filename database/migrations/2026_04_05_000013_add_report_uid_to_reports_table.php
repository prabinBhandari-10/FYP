<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('report_uid', 32)->nullable()->after('id');
        });

        DB::table('reports')
            ->select('id')
            ->orderBy('id')
            ->lazy()
            ->each(function (object $report): void {
                do {
                    $uid = 'RPT-' . strtoupper(Str::random(8));
                } while (DB::table('reports')->where('report_uid', $uid)->exists());

                DB::table('reports')
                    ->where('id', $report->id)
                    ->update(['report_uid' => $uid]);
            });

        Schema::table('reports', function (Blueprint $table) {
            $table->unique('report_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropUnique(['report_uid']);
            $table->dropColumn('report_uid');
        });
    }
};
