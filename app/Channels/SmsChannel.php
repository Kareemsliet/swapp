<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);

        $phone = data_get($message, 'phone');
        $body = data_get($message, 'body');

        if (!$phone || !$body) {
            return;
        }

        $this->handle($phone, $body);
    }

    protected function handle(string $phone, string $body): void
    {
        // implement your SMS sending logic here.
    }
}

