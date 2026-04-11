<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_uid',
        'user_id',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'title',
        'description',
        'color',
        'type',
        'category',
        'location',
        'latitude',
        'longitude',
        'date',
        'image',
        'status',
        'is_anonymous',
        'urgency',
        'payment_pidx',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class, 'item_id');
    }

    public function sightings(): HasMany
    {
        return $this->hasMany(Sighting::class);
    }

    public function foundResponses(): HasMany
    {
        return $this->hasMany(FoundResponse::class);
    }

    public function approvedClaims(): HasMany
    {
        return $this->hasMany(Claim::class, 'item_id')->where('status', 'approved');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReportImage::class)->orderBy('sort_order');
    }

    public function isUrgent(): bool
    {
        return $this->urgency === 'urgent';
    }

    public function scopeNotDeleted(Builder $query): Builder
    {
        return $query->where('status', '!=', 'deleted');
    }

    public function requiresPayment(): bool
    {
        return $this->isUrgent() && $this->payment_status !== 'completed';
    }

    public function paymentPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    protected static function booted(): void
    {
        static::creating(function (self $report): void {
            if (! empty($report->report_uid)) {
                return;
            }

            do {
                $uid = 'RPT-' . strtoupper(Str::random(8));
            } while (self::query()->where('report_uid', $uid)->exists());

            $report->report_uid = $uid;
        });
    }
}
