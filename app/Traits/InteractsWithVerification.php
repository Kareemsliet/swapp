<?php

namespace App\Traits;

use Carbon\Carbon;

trait InteractsWithVerification
{
    public function hasVerifiedBy(string $type = 'email')
    {
        $column = "{$type}_verified_at";

        return !is_null($this->{$column});
    }

    public function getVerifiedMeta(string $type = 'email')
    {
        $meta = $this->verified_meta ?? [];

        return data_get($meta, $type, null);
    }

    public function getCodeVerification(string $type = 'email')
    {
        $meta = (array) $this->getVerifiedMeta($type) ?? [];

        return data_get($meta, "code", null);
    }

    public function getExpiresAtVerification(string $type = 'email')
    {
        $meta = (array) $this->getVerifiedMeta($type) ?? [];

        return data_get($meta, "expires_at", null);
    }

    public function isExpiredVerification(string $type = 'email')
    {
        if (is_null($this->getExpiresAtVerification($type))) {
            return false;
        }

        return now()->greaterThan(Carbon::parse($this->getExpiresAtVerification($type)));
    }

    public function generateVerificationMeta(string $type = 'email')
    {
        $columnAt = "{$type}_verified_at";

        $meta = array_merge(
            (array) $this->verified_meta,
            [
                $type => [
                    "code" => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                    "expires_at" => now()->addMinutes(config('auth.verification.expire', 10))->toDateTimeString(),
                ],
            ]
        );

        $this->verified_meta = $meta;
        $this->{$columnAt} = null;

        $this->save();

        return $meta;
    }


    public function markAsVerified(string $type = 'email')
    {
        $columnAt = "{$type}_verified_at";

        $this->{$columnAt} = now();

        return $this->save();
    }
}

