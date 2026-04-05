<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'related_report_id',
        'related_claim_id',
        'is_read',
        'is_email_sent',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_email_sent' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'related_report_id');
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class, 'related_claim_id');
    }
}
