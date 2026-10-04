<?php

namespace App\Traits;

use App\Models\ConfirmationCode;
use Illuminate\Support\Str;

trait Confirmable
{
    /**
     * Generate a new confirmation code for this model.
     */
    public function generateConfirmationCode(string $type, int $expiresInMinutes = 60): ConfirmationCode
    {
        // Invalidate any existing codes of the same type
        $this->confirmationCodes()
            ->where('type', $type)
            ->update(['used_at' => now()]);

        return $this->confirmationCodes()->create([
            'code' => $this->generateSixDigitCode(), // Generate 6-digit numeric code
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }

    /**
     * Generate a 6-digit numeric confirmation code
     */
    protected function generateSixDigitCode(): string
    {
        return str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get all confirmation codes for this model.
     */
    public function confirmationCodes()
    {
        return $this->morphMany(ConfirmationCode::class, 'confirmable');
    }

    /**
     * Find a valid confirmation code.
     */
    public function findValidCode(string $code, string $type): ?ConfirmationCode
    {
        return $this->confirmationCodes()
            ->valid()
            ->where('code', $code)
            ->where('type', $type)
            ->first();
    }
}
