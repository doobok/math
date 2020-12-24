<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramSendReport extends Notification
{
    use Queueable;

    public function __construct($type, $period, $lessons, $wage, $profit, $lessons_count, $students_count, $pass_count, $pass_notpayed_count, $pays_in, $pays_out, $pays_profit, $errors)
    {
        $this->type = $type;
        $this->period = $period;
        $this->lessons = $lessons;
        $this->wage = $wage;
        $this->profit = $profit;
        $this->lessons_count = $lessons_count;
        $this->students_count = $students_count;
        $this->pass_count = $pass_count;
        $this->pass_notpayed_count = $pass_notpayed_count;
        $this->pays_in = $pays_in;
        $this->pays_out = $pays_out;
        $this->pays_profit = $pays_profit;
        $this->errors = $errors;
    }

     public function via($notifiable)
     {
         return [TelegramChannel::class];
     }

     public function toTelegram($notifiable)
     {
         return TelegramMessage::create()
             ->to(config('app.telegramchat'))
             ->content("📈 *Звіт $this->type за $this->period* \nкількість занять *$this->lessons_count* учнів *$this->students_count* пропусків *$this->pass_count* неоплатних *$this->pass_notpayed_count* \nдохід за уроки *$this->lessons грн.* комісія тьюторів *$this->wage грн.* профіт *$this->profit грн.* \nвнесенно *$this->pays_in грн.* видатки *$this->pays_out грн.* різниця *$this->pays_profit грн.* \nпомилки: доходи/копіювання уроків/оплати *$this->errors*");
     }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
