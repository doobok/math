<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram;
use App\Report;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;

class ReportsBotController extends Controller
{
    // обробляємо колбеки
    public function callBacks($callback, $state, $chat_id)
    {

      $params = $callback['callback_query']['data'];
      $state->update([
        'action' => $callback['callback_query']['message']['message_id'],
        'params' => $params
      ]);

      $data = json_decode($callback['callback_query']['data']);

      switch ($data->action) {
        case 'mainmenu':
            self::mainMenu($chat_id);
          break;

        case 'slide':
            self::slideReport($data, $state, $chat_id);
          break;

        case 'showlist':
            self::getList($data, $state, $chat_id);
          break;

        case 'go':
            self::getList($data, $state, $chat_id);
          break;

      }

    }

    // обробляємо звичайний ввід
    public function textInputs($input, $state, $chat_id)
    {
      self::getReport($input, $state, $chat_id);
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

        $keyboard = [
          [
            // ['text'=>'⬅ 1 сторінка','callback_data'=>json_encode(['action'=>'go','type'=>$liveparams->type,'offset'=>0,])],
            ['text'=>'🔙 Змінити тип','callback_data'=>json_encode(['action'=>'mainmenu','type'=>null,'offset'=>0,])],
            ['text'=>'Наступна сторінка ➡','callback_data'=>json_encode(['action'=>'go','type'=>$params->type,'offset'=>$params->offset + 10,])],
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

      $response = Telegram::editMessageText([
          'chat_id' => $chat_id,
          'message_id' => $state['action'],
          'text' => $text,
          'reply_markup' => $encodedMarkup,
      ]);

    }

    public function getReport($input, $state, $chat_id)
    {
      $report = Report::where('id', $input)->first();
      if ($report) {

        $text = self::reportBody($report);

        $keyboard = [
          [
            ['text'=>'⬅ Наступний','callback_data'=>json_encode(['action'=>'slide','type'=>$report->type,'offset'=>-1,'id'=>$report->id])],
            ['text'=>'До меню звітів','callback_data'=>json_encode(['action'=>'mainmenu','type'=>null,'offset'=>0,])],
            ['text'=>'Попередній ➡','callback_data'=>json_encode(['action'=>'slide','type'=>$report->type,'offset'=>1,'id'=>$report->id])],
          ]
        ];
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
          'chat_id' => $chat_id,
          'text' => $text,
          'reply_markup' => $encodedMarkup,
      ]);

    }

    public function slideReport($data, $state, $chat_id)
    {
      if ($data->offset > 0) {
        $report = Report::where('type', $data->type)->orderBy('id', 'desc')->where('id', '<', $data->id)->first(); // old
      } else {
        $report = Report::where('type', $data->type)->orderBy('id')->where('id', '>', $data->id)->first() ;// new
      }

        if ($report) {

          $text = self::reportBody($report);

          $keyboard = [
            [
              ['text'=>'⬅ Наступний','callback_data'=>json_encode(['action'=>'slide','type'=>$report->type,'offset'=>-1,'id'=>$report->id])],
              ['text'=>'До меню звітів','callback_data'=>json_encode(['action'=>'mainmenu','type'=>null,'offset'=>0,])],
              ['text'=>'Попередній ➡','callback_data'=>json_encode(['action'=>'slide','type'=>$report->type,'offset'=>1,'id'=>$report->id])],
            ]
          ];
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

        Telegram::editMessageText([
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $encodedMarkup,
            'message_id' => $state['action'],
        ]);
    }

    public function mainMenu($chat_id)
    {
      $keyboard = [
        [
          ['text'=>'🌞 Денний','callback_data'=>json_encode(['action'=>'showlist','type'=>'daily','offset'=>0,])],
          ['text'=>'📅 Тижневий','callback_data'=>json_encode(['action'=>'showlist','type'=>'weekly','offset'=>0,])],
          ['text'=>'🌖 Місячний','callback_data'=>json_encode(['action'=>'showlist','type'=>'monthly','offset'=>0,])],
          ['text'=>'📊 Квартальний','callback_data'=>json_encode(['action'=>'showlist','type'=>'quarterly','offset'=>0,])],
        ]
      ];

      $reply_markup = Keyboard::make([
         'inline_keyboard' => $keyboard,
         'resize_keyboard' => true,
         'one_time_keyboard' => true
      ]);
      $encodedMarkup = json_encode($reply_markup);

      $response = Telegram::sendMessage([
          'text' => 'Оберіть тип звіту, щоб отримати список',
          'reply_markup' => $encodedMarkup,
          'chat_id' => $chat_id
      ]);
    }

    public function reportBody($report)
    {
        $text = '🐼 Це звіт який я знайшов для тебе:' .PHP_EOL. PHP_EOL;

        $text .= sprintf('👉 Звіт ID:%s тип: %s, за %s' . PHP_EOL . PHP_EOL, $report->id, $report->type, $report->period);
        $text .= sprintf('📚 всього занять: %s, 👦 учнів: %s' . PHP_EOL, $report->lessons_count, $report->students_count);
        $text .= sprintf('📛 пропусків: %s, 🐔 з них неоплатних: %s' . PHP_EOL, $report->pass_count, $report->pass_notpayed_count);
        $text .= sprintf('💰 загальний прибуток: %s, 🎓 дохід тьюторів: %s' . PHP_EOL, $report->lessons, $report->wage);
        $text .= sprintf('💲 профіт: %s' . PHP_EOL, $report->profit);
        $text .= '💰 Фінансова інформація:' . PHP_EOL;
        $text .= sprintf('↘ загальні надходження: %s, ↗ видатки: %s' . PHP_EOL, $report->pays_in, $report->pays_out);
        $text .= sprintf('💲 чистий профіт: %s' . PHP_EOL, $report->pays_profit);

        $text .= PHP_EOL. 'Ти можеш переміщатись між сусідніми звітами відібраними за типом, використовуючи кнопки навігації:';

        return $text;
    }
}
