<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'message',
        'citizenship_document_path',
        'proof_text',
        'proof_photo_path',
        'status',
        'held_at',
    ];

    protected function casts(): array
    {
        return [
            'held_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'item_id');
    }

    public function chatConversation(): HasOne
    {
        return $this->hasOne(ChatConversation::class);
    }
}
