<?php

namespace App\Traits;

trait InteractsWithResetPassword
{
    public function generatePasswordResetMeta(): array
    {
        $code = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10)->toDateTimeString();

        $meta = [
            'code' => $code,
            'expires_at' => $expiresAt,
        ];

        $this->password_reset_meta = $meta;
        $this->save();

        return $meta;
    }

    public function getPasswordResetMeta()
    {
        return $this->password_reset_meta ?? [];
    }

    public function getCodePasswordReset()
    {
        return data_get($this->getPasswordResetMeta(), 'code', null);
    }

    public function getExpiresAtPasswordReset()
    {
        $meta = $this->getPasswordResetMeta();

        return data_get($meta, 'expires_at', null);
    }

    public function isExpiredPasswordReset(): bool
    {
        if (is_null($this->getExpiresAtPasswordReset())) {
            return false;
        }

        return now()->greaterThan(\Carbon\Carbon::parse($this->getExpiresAtPasswordReset()));
    }
}
