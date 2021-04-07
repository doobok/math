<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram;
use App\Lesson;
use Telegram\Bot\Keyboard\Keyboard;
use Carbon\Carbon;

class SheduleBotController extends Controller
{
    // обробляємо колбеки
    public function callBacks($callback, $state, $chat_id)
    {
        // визначаємо натиснуту кнопку
        $params = json_decode($callback['callback_query']['data']);
        if ($params) {
          switch ($params->day) {
            case 'soon':
              $lessons = self::getSoon($params, $state, $chat_id);
              break;
            case 'today':
              $lessons = self::getToday($params, $state, $chat_id);
              break;
            case 'tomorrow':
              $lessons = self::getTomorrow($params, $state, $chat_id);
              break;
            case 'aftertomorrow':
              $lessons = self::getATomorrow($params, $state, $chat_id);
              break;
          }
          // збираємо повідомлення
          if (count($lessons) > 0) {
            $response = '🐼 Ось заняття за твоїм запитом:'. PHP_EOL. PHP_EOL;
            foreach ($lessons as $lesson) {
                if ($lesson->students == $lesson->pass) {
                  $response .= sprintf('❌ %s' . PHP_EOL, $lesson->start);
                } else {
                  $response .= sprintf('👉 %s' . PHP_EOL, $lesson->start);
                }

                if ($lesson->classroom_id == 3) {
                  $response .= sprintf('   клас %s 🌏' . PHP_EOL, $lesson->name);
                } else {
                  $response .= sprintf('   клас %s (cab-%s)' . PHP_EOL, $lesson->name, $lesson->classroom_id);
                }

            }
            $response .= PHP_EOL . 'всього занять: '. count($lessons) .PHP_EOL. 'головне меню розкладу /shedule';
          } else {
            $response = '🐼 Я не знайшов жодного уроку на обраний період';
          }

          // і виводимо його
          Telegram::sendMessage([
           'chat_id' => $chat_id,
           'text' => $response
          ]);
        } else {
          // фіксим натиснення кнопок не за сценарієм
          Telegram::sendMessage([
           'chat_id' => $chat_id,
           'text' => '🐼 Ти явно, щось робиш не так.' .PHP_EOL.'Давай почнемо з початку /shedule' .PHP_EOL.'Або навіть з самого початку /start'
          ]);
        }
    }

    // обробляємо звичайний ввід
    public function textInputs($input, $state, $chat_id)
    {
      Telegram::sendMessage([
       'chat_id' => $chat_id,
       'text' => '🐼 Не мудруй!' .PHP_EOL.'Просто натисни на потрібну кнопку' .PHP_EOL.'Команда /shedule не передбачає текстового вводу'
      ]);
    }

    //////////////////////////////////////////////////////////
    // службові функції
    public function getSoon($params, $state, $chat_id)
    {
      return Lesson::where('tutor_id', $params->id)->where('start', '>', Carbon::now()->toDateTimeString())->orderBy('start')->limit(1)->get();
    }
    public function getToday($params, $state, $chat_id)
    {
      return Lesson::where('tutor_id', $params->id)->whereDate('start', '=', Carbon::today()->toDateString())->orderBy('start')->get();
    }
    public function getTomorrow($params, $state, $chat_id)
    {
      return Lesson::where('tutor_id', $params->id)->whereDate('start', '=', Carbon::today()->addDays(1)->toDateString())->orderBy('start')->get();
    }
    public function getATomorrow($params, $state, $chat_id)
    {
      return Lesson::where('tutor_id', $params->id)->whereDate('start', '=', Carbon::today()->addDays(2)->toDateString())->orderBy('start')->get();
    }
}
