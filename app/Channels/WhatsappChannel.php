<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class WhatsappChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if(!method_exists($notification, 'toWhatsapp')) {
            return;
        }

        $message = $notification->toWhatsapp($notifiable);

        $phone = data_get($message, 'phone');
        $body = data_get($message, 'body');

        if(!$phone || !$body) {
            return;
        }
 
        $twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $twilio->messages->create(
            "whatsapp:{$phone}",
            [
                "from" => config('services.twilio.whatsapp_from'),
                "body" => $body
            ]
        );
    }
}

