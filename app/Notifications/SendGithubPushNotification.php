<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SendGithubPushNotification extends Notification
{
    use Queueable;
    public function __construct(private mixed $data)
    {
    }


    public function via($notifiable)
    {
        return ['telegram'];
    }
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
         //  ->to('-868088992')
            ->content(json_encode($this->data));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
