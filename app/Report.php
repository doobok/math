<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TelegramSendReport;

class Report extends Model
{
    // відправляємо звіт
    protected static function boot()
    {
        static::saving(function($instance)
        {

            $type = $instance->type;
            $period = $instance->period;
            $lessons = $instance->lessons;
            $wage = $instance->wage;
            $profit = $instance->profit;
            $lessons_count = $instance->lessons_count;
            $students_count = $instance->students_count;
            $pass_count = $instance->pass_count;
            $pass_notpayed_count = $instance->pass_notpayed_count;
            $pays_in = $instance->pays_in;
            $pays_out = $instance->pays_out;
            $pays_profit = $instance->pays_profit;
            $errors = $instance->errors;

            Notification::send('', new TelegramSendReport($type, $period, $lessons, $wage, $profit, $lessons_count, $students_count, $pass_count, $pass_notpayed_count, $pays_in, $pays_out, $pays_profit, $errors));

        });
        parent::boot();
    }
}
