<?php

namespace App\Notifications;

use App\Data\Payload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Support\Str;

class SendGithubPushNotification extends Notification
{
    use Queueable;
    public function __construct(private Payload $payload)
    {
    }


    public function via($notifiable)
    {
        return ['telegram'];
    }
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
         // // ->to('-868088992')
            ->content($this->payload->content())
            ->button('View on Github', $this->payload->url());
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
