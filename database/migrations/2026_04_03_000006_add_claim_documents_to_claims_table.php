<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('citizenship_document_path')->nullable()->after('message');
            $table->string('proof_photo_path')->nullable()->after('proof_text');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['citizenship_document_path', 'proof_photo_path']);
        });
    }
};
