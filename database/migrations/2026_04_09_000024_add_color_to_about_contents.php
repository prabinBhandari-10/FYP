<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('about_contents', 'color')) {
                $table->string('color')->default('blue')->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('about_contents', function (Blueprint $table) {
            if (Schema::hasColumn('about_contents', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
