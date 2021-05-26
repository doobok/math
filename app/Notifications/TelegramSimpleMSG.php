<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramSimpleMSG extends Notification
{
    use Queueable;

    public function __construct($msg)
    {
      $this->msg = $msg;
    }

     public function via($notifiable)
     {
         return [TelegramChannel::class];
     }

     public function toTelegram($notifiable)
     {

         return TelegramMessage::create()
             ->to(config('app.telegramchat'))
             ->content("☑ $this->msg");
     }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
