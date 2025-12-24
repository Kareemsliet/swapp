<?php

namespace App\Listeners;

use App\Events\ClientRegistered;
use App\Notifications\SendVerificationCode;

class registerClient
{
    /**
     * Handle the event.
     */
    public function handle(ClientRegistered $event): void
    {
        $client = $event->client;

        $preferredContactMethod = $client->email ? 'email' : 'phone';

        if (!$client->hasVerifiedBy($preferredContactMethod)) {

            $meta = $client->generateVerificationMeta($preferredContactMethod);

            $code = data_get($meta, "{$preferredContactMethod}.code");

            if(!$code) {
                return;
            }

            $client->notify(new SendVerificationCode($client, $code, $preferredContactMethod));

        }
    }
}
