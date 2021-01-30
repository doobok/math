<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram;
use App\Report;
use Telegram\Bot\Keyboard\Keyboard;

class ReportsBotController extends Controller
{
    // обробляємо колбеки
    public function callBacks($callback, $state, $chat_id)
    {
      // $t = sprintf('%s: %s'.PHP_EOL, 'TEST', $callback['callback_query']['message']['message_id']);
      // Telegram::sendMessage([
      //    'chat_id' => $chat_id,
      //    'text' => $t
      //   ]);

      switch ($state['action']) {
        case 'value':
          // code...
          break;

        default:
        // $params = $callback['callback_query']['data'];
        // //
        // $prms = json_decode($callback['callback_query']['data']);
        //
        // Telegram::sendMessage([
        //    'chat_id' => $chat_id,
        //    'text' => $prms->action
        //   ]);


        $params = $callback['callback_query']['data'];

          $state->update([
            'action' => $callback['callback_query']['message']['message_id'],
            'params' => $params
          ]);
          self::getList($params, $state, $chat_id);

          break;
      }
      // $params = $callback['callback_query']['data'];
      //
      // $test = sprintf('%s: %s'.PHP_EOL, 'TEST', $params->type);
      // Telegram::sendMessage([
      //    'chat_id' => $chat_id,
      //    // 'text' => $callback
      //    'text' => 'tut'
      //   ]);
        // // визначаємо натиснуту кнопку
        // $query = $callback['callback_query'];
        // if ($query) {
        // Telegram::sendMessage([
        //    'chat_id' => $chat_id,
        //    'text' => $query
        //   ]);
        // }
        // if ($params) {
        //   switch ($params->type) {
        //     case 'soon':
        //       $lessons = self::getSoon($params, $state, $chat_id);
        //       break;
        //     case 'today':
        //       $lessons = self::getToday($params, $state, $chat_id);
        //       break;
        //     case 'tomorrow':
        //       $lessons = self::getTomorrow($params, $state, $chat_id);
        //       break;
        //     case 'aftertomorrow':
        //       $lessons = self::getATomorrow($params, $state, $chat_id);
        //       break;
        //   }
        //   // збираємо повідомлення
        //   if (count($lessons) > 0) {
        //     $response = '🐼 Ось заняття за твоїм запитом:'. PHP_EOL. PHP_EOL;
        //     foreach ($lessons as $lesson) {
        //         $response .= sprintf('👉 %s' . PHP_EOL, $lesson->start);
        //         $response .= sprintf('   клас %s (cab-%s)' . PHP_EOL, $lesson->name, $lesson->classroom_id);
        //     }
        //     $response .= PHP_EOL . 'всього занять: '. count($lessons) .PHP_EOL. 'головне меню розкладу /shedule';
        //   } else {
        //     $response = '🐼 Я не знайшов жодного уроку на обраний період';
        //   }
        //
        //   // і виводимо його
        //   Telegram::sendMessage([
        //    'chat_id' => $chat_id,
        //    'text' => $response
        //   ]);
        // } else {
        //   // фіксим натиснення кнопок не за сценарієм
        //   Telegram::sendMessage([
        //    'chat_id' => $chat_id,
        //    'text' => '🐼 Ти явно, щось робиш не так.' .PHP_EOL.'Давай почнемо з початку /shedule' .PHP_EOL.'Або навіть з самого початку /start'
        //   ]);
        // }
    }

    // обробляємо звичайний ввід
    public function textInputs($input, $state, $chat_id)
    {

      self::getReport($input, $state, $chat_id);

      // Telegram::sendMessage([
      //  'chat_id' => $chat_id,
      //  'text' => '🐼 Не мудруй!' .PHP_EOL.'Просто натисни на потрібну кнопку' .PHP_EOL.'Команда /reports не передбачає текстового вводу'
      // ]);
    }

    //////////////////////////////////////////////////////////
    // службові функції

    public function getList($liveparams, $state, $chat_id)
    {
      $params = json_decode($state['params']);

      $reports = Report::where('type', $params->type)->orderBy('id', 'desc')->offset($params->offset)->limit(10)->get();

      if (count($reports) > 0) {
        $text = '🐼 Ось звіти за твоїм запитом:'. PHP_EOL. 'Щоб переглянути звіт, просто відправ мені його ID' .PHP_EOL. PHP_EOL;
        foreach ($reports as $report) {

          $text .= sprintf('👉 ID:%s тип: %s, за період: %s ' . PHP_EOL, $report->id, $report->type, $report->period);

        }

        if ($params->offset >= 10) {
          $btn1offset = $params->offset - 10;
        } else {
          $btn1offset = 0;
        }

        $keyboard = [
          [
            ['text'=>'⬅ Новіші','callback_data'=>json_encode(['action'=>'go','type'=>$params->type,'offset'=>$btn1offset,])],
            ['text'=>'Змінити тип','callback_data'=>json_encode(['action'=>'mainmenu','type'=>null,'offset'=>0,])],
            ['text'=>'Старіші ➡','callback_data'=>json_encode(['action'=>'go','type'=>$params->type,'offset'=>$params->offset + 10,])],
          ]
        ];
        $text .= PHP_EOL. 'Для навігації використовуй кнопки:';

      } else {
        $keyboard = [
          [
             ['text'=>'🔙 Повернутись назад','callback_data'=>json_encode(['action'=>'mainmenu','type'=>null,'offset'=>0,])],
          ]
        ];
        $text = '🐼 Я не знайшов жодного звіту';
      }

      $reply_markup = Keyboard::make([
         'inline_keyboard' => $keyboard,
         'resize_keyboard' => true,
         'one_time_keyboard' => true
      ]);
      $encodedMarkup = json_encode($reply_markup);

      $response = Telegram::sendMessage([
          'text' => $text,
          'reply_markup' => $encodedMarkup,
          'chat_id' => $chat_id
      ]);

    }

    public function getReport($input, $state, $chat_id)
    {
      $report = Report::where('id', $input)->first();
      if ($report) {
        $text = '🐼 Це звіт який я знайшов для тебе:' .PHP_EOL. PHP_EOL;

        $text .= sprintf('👉 Звіт ID:%s тип: %s, за %s' . PHP_EOL . PHP_EOL, $report->id, $report->type, $report->period);
        $text .= sprintf('📚 всього занять: %s, 👦 учнів: %s' . PHP_EOL, $report->lessons_count, $report->students_count);
        $text .= sprintf('📛 пропусків: %s, 🐔 з них неоплатних: %s' . PHP_EOL, $report->pass_count, $report->pass_notpayed_count);
        $text .= sprintf('💰 загальний прибуток: %s, 🎓 дохід тьюторів: %s' . PHP_EOL, $report->lessons, $report->wage);
        $text .= sprintf('💲 профіт: %s' . PHP_EOL, $report->profit);
        $text .= '💰 Фінансова інформація:' . PHP_EOL;
        $text .= sprintf('↘ загальні надходження: %s, ↗ видатки: %s' . PHP_EOL, $report->pays_in, $report->pays_out);
        $text .= sprintf('💲 чистий профіт: %s' . PHP_EOL, $report->pays_profit);

        $keyboard = [
          [
            ['text'=>'⬅ Новіші','callback_data'=>json_encode(['action'=>'slide','type'=>$report->type,'offset'=>-1,])],
            ['text'=>'Змінити тип','callback_data'=>json_encode(['action'=>'mainmenu','type'=>null,'offset'=>0,])],
            ['text'=>'Старіші ➡','callback_data'=>json_encode(['action'=>'slide','type'=>$report->type,'offset'=>1,])],
          ]
        ];
        $text .= PHP_EOL. 'Ти можеш переміщатись між сусідніми звітами відібраними за типом, використовуючи кнопки навігації:';
      } else {
        $keyboard = [
          [
             ['text'=>'🔙 До меню звітів','callback_data'=>json_encode(['action'=>'mainmenu','type'=>null,'offset'=>0,])],
          ]
        ];
        $text = '🐼 Звіту з таким ідентифікатором немає. Давай! Спробуй ще раз!';
      }
      $reply_markup = Keyboard::make([
         'inline_keyboard' => $keyboard,
         'resize_keyboard' => true,
         'one_time_keyboard' => true
      ]);
      $encodedMarkup = json_encode($reply_markup);

      Telegram::sendMessage([
          'text' => $text,
          'reply_markup' => $encodedMarkup,
          'chat_id' => $chat_id
      ]);

    }
}
