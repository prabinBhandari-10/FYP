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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['similar_item', 'claim_received', 'claim_approved', 'claim_rejected', 'report_comment']);
            $table->string('title');
            $table->text('message');
            $table->foreignId('related_report_id')->nullable()->constrained('reports')->onDelete('set null');
            $table->foreignId('related_claim_id')->nullable()->constrained('claims')->onDelete('set null');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_email_sent')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
