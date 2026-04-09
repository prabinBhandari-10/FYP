<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

        if (! $this->tableExists('claims_old')) {
            if ($this->tableExists('claims')) {
                DB::statement('ALTER TABLE claims RENAME TO claims_old');
            }

            $this->createClaimsTable();
            $this->createUniqueIndex('claims', 'claims_user_id_item_id_unique_v2');
        } elseif (! $this->tableExists('claims')) {
            $this->createClaimsTable();
            $this->createUniqueIndex('claims', 'claims_user_id_item_id_unique_v2');
        } else {
            $this->createUniqueIndex('claims', 'claims_user_id_item_id_unique_v2');
        }

        if ($this->tableExists('claims_old')) {
            $rows = DB::table('claims_old')
                ->orderBy('id')
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'user_id' => $row->user_id,
                        'item_id' => $row->item_id,
                        'message' => $row->message,
                        'citizenship_document_path' => $row->citizenship_document_path ?? null,
                        'proof_text' => $row->proof_text ?? null,
                        'proof_photo_path' => $row->proof_photo_path ?? null,
                        'status' => $row->status,
                        'held_at' => $row->held_at,
                        'payment_required' => $row->payment_required ?? 0,
                        'payment_amount' => $row->payment_amount ?? null,
                        'payment_reason' => $row->payment_reason ?? null,
                        'payment_status' => $row->payment_status ?? null,
                        'payment_pidx' => $row->payment_pidx ?? null,
                        'payment_completed_at' => $row->payment_completed_at ?? null,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                    ];
                })
                ->all();

            DB::table('claims')->insertOrIgnore($rows);

            DB::statement('DROP TABLE claims_old');
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        Schema::disableForeignKeyConstraints();

        if ($this->tableExists('claims_old')) {
            DB::statement('DROP TABLE claims_old');
        }

        if ($this->tableExists('claims')) {
            DB::statement('ALTER TABLE claims RENAME TO claims_new');
        }

        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('reports')->cascadeOnDelete();
            $table->text('message');
            $table->text('citizenship_document_path')->nullable();
            $table->text('proof_text')->nullable();
            $table->text('proof_photo_path')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('held_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'item_id']);
        });

        if ($this->tableExists('claims_new')) {
            $rows = DB::table('claims_new')
                ->orderBy('id')
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'user_id' => $row->user_id,
                        'item_id' => $row->item_id,
                        'message' => $row->message,
                        'citizenship_document_path' => $row->citizenship_document_path ?? null,
                        'proof_text' => $row->proof_text ?? null,
                        'proof_photo_path' => $row->proof_photo_path ?? null,
                        'status' => in_array($row->status, ['pending', 'approved', 'rejected'], true) ? $row->status : 'pending',
                        'held_at' => $row->held_at,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                    ];
                })
                ->all();

            DB::table('claims')->insertOrIgnore($rows);
            DB::statement('DROP TABLE claims_new');
        }

        Schema::enableForeignKeyConstraints();
    }

    private function tableExists(string $table): bool
    {
        return DB::table('sqlite_master')
            ->where('type', 'table')
            ->where('name', $table)
            ->exists();
    }

    private function indexExists(string $index): bool
    {
        return DB::table('sqlite_master')
            ->where('type', 'index')
            ->where('name', $index)
            ->exists();
    }

    private function createClaimsTable(): void
    {
        if ($this->tableExists('claims')) {
            return;
        }

        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('reports')->cascadeOnDelete();
            $table->text('message');
            $table->text('citizenship_document_path')->nullable();
            $table->text('proof_text')->nullable();
            $table->text('proof_photo_path')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('held_at')->nullable();
            $table->boolean('payment_required')->default(false);
            $table->unsignedInteger('payment_amount')->nullable();
            $table->string('payment_reason')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_pidx')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->timestamps();
        });
    }

    private function createUniqueIndex(string $table, string $indexName): void
    {
        if ($this->indexExists($indexName)) {
            return;
        }

        DB::statement('CREATE UNIQUE INDEX ' . $indexName . ' ON ' . $table . ' (user_id, item_id)');
    }
};
