<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'confirmable_id',
        'confirmable_type',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Get the parent confirmable model (user, admin, etc.).
     */
    public function confirmable()
    {
        return $this->morphTo("users");
    }

    /**
     * Check if the code is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the code has been used.
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Mark the code as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * Scope a query to only include valid codes.
     */
    public function scopeValid($query)
    {
        return $query->whereNull('used_at')
            ->where('expires_at', '>', now());
    }
}
