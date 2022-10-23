<?php

namespace App\Notifications;

use App\Data\Payload;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;

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
        return TelegramFile::create()
            ->content($this->payload->content())
            ->photo($this->payload->image())
            ->button('View on Github', $this->payload->url());
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
