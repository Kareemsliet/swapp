<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendVerificationCode extends Notification implements ShouldQueue
{
    use Queueable;

    public Client $client;

    public string $type;

    public string $code;

    /**
     * Create a new notification instance.
     */
    public function __construct(Client $client, string $code, string $type)
    {
        $this->client = $client;
        $this->type = $type;
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->type === 'phone' ? [SmsChannel::class] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->view('mail.verification', [
            "client" => $this->client,
            "code" => $this->code,
        ])->subject("رمز التحقق - SWAPP");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toSms(object $notifiable): array
    {
        $message = "مرحباً *{$this->client->name}*\n\n";
        $message .= "رمز التحقق الخاص بك في *SWAPP* هو:\n\n";
        $message .= "*{$this->code}*\n\n";
        $message .= "لا تشارك هذا الرمز مع أي شخص\n\n";
        $message .= "شكراً لاستخدامك SWAPP!";

        return [
            "body" => $message,
            "phone" => $this->client->phone,
        ];
    }
}
