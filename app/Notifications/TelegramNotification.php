<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use JsonSerializable;
use NotificationChannels\Telegram\TelegramBase;

class TelegramNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly TelegramBase $notification)
    {
    }


    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): JsonSerializable
    {
        return $this->notification;
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
