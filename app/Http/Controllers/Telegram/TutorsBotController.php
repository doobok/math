<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram;
use App\Tutor;
use Telegram\Bot\Keyboard\Keyboard;

class TutorsBotController extends Controller
{
    // обробляємо колбеки
    public function callBacks($callback, $state, $chat_id)
    {
      switch ($state['action']) {
        case 'confirm':
        // пʼята дія (підтвердження поповнення)
          switch ($callback['callback_query']['data']) {
            case 'confirm':
                self::confirmPay($state, $chat_id);
              break;
            case 'cancel':
                Telegram::sendMessage([
                  'chat_id' => $chat_id,
                  'text' => '⚠ Операція скасована...'
                ]);
              break;
          }
          $state->update(['command' => '/tutors','action' => null,'params' => null]);//обнуляємо стейт
            Telegram::sendMessage([
              'chat_id' => $chat_id,
              'text' => '🐼 Мені сподобалось працювати з тьюторами! А тобі? Давай ще раз на них подивимось! Натискай /tutors'
            ]);
          break;

        default:
        // перша дія (визначаємо натиснуту кнопку)
          $params = json_decode($callback['callback_query']['data']);
          if ($params) {
            switch ($params->action) {
              case 'show':
                  return self::showTutor($params, $state, $chat_id);
                break;
              case 'pay':
                  $state->update([
                    'action' => 'pay',
                    'params' => json_encode(['id' => $params->id])
                  ]);
                  Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => '💲 Введи суму, яку видаєш'
                  ]);
                break;
            }
          } else {
            // фіксим натиснення кнопок не за сценарієм
            $state->update(['command' => '/tutors','action' => null,'params' => null]);//обнуляємо стейт
            Telegram::sendMessage([
             'chat_id' => $chat_id,
             'text' => '🐼 Ти явно, щось робиш не так.' .PHP_EOL.'Давай почнемо з початку /tutors' .PHP_EOL.'Або навіть з самого початку /start'
            ]);
          }
          break;
      }
    }

    // обробляємо звичайний ввід
    public function textInputs($input, $state, $chat_id)
    {
      switch ($state['action']) {
        // друга дія (вводимо суму виплати)
        case 'pay':
            self::paySumm($input, $state, $chat_id);
          break;

        default:
            Telegram::sendMessage([
             'chat_id' => $chat_id,
             'text' => '🐼 Я тебе не розумію.' .PHP_EOL.'Можливо, очікується натиснення на кнопку?' .PHP_EOL.'А інколи варто почати з команди /start'
            ]);
          break;
      }
    }

    public function showTutor($params, $state, $chat_id)
    {
      $state->update([
        'action' => 'show',
        'params' => json_encode(['id' => $params->id])
      ]);
      $tutor = Tutor::findorfail($params->id);
      $string = sprintf('ID%s: 🎓 %s %s %s' . PHP_EOL, $tutor->id, $tutor->lname, $tutor->name, $tutor->mname );
      $string .= sprintf('%s: %s %s' . PHP_EOL, '💵 Баланс', $tutor->balance, 'грн' );
      $string .= sprintf('%s: %s' . PHP_EOL, '📞 Телефон', $tutor->phone );
      $string .= sprintf('%s' . PHP_EOL, $tutor->comment );

        Telegram::sendMessage([
          'chat_id' => $chat_id,
          'text' => $string
        ]);
    }

    //////////////////////////////////////////////////////////
    // службові функції
    public function paySumm($input, $state, $chat_id)
    {
      if (filter_var ( $input, FILTER_VALIDATE_INT )) {
        if ($input > 0) {
          // отримуємо параметри
          $params = json_decode($state['params']);
          // обновляєм стейт
          $state->update([
            'action' => 'confirm',
            'params' => json_encode(['id' => $params->id, 'summ' => $input])
          ]);
          // отримуємо учня
          $tutor = Tutor::findorfail($params->id);
          // формуємо відповідь
          $keyboard = [
            [
              ['text'=>'✅ Підтвердити','callback_data'=>'confirm'],
              ['text'=>'🚫 Скасувати','callback_data'=>'cancel'],
            ],
          ];
          $string = sprintf('%s:%s' . PHP_EOL, '❓ Видати зарплату тьютору з ID', $tutor->id );
          $string .= sprintf('%s %s %s, %s %s %s' . PHP_EOL, $tutor->lname, $tutor->name, $tutor->mname, 'з поточним балансом 💵', $tutor->balance, 'грн?' );
          $string .= sprintf('%s %s %s' . PHP_EOL, 'До видачі 💵', $input, 'грн' );

          $reply_markup = Keyboard::make([
             'inline_keyboard' => $keyboard,
             'resize_keyboard' => true,
             'one_time_keyboard' => true
          ]);
          $encodedMarkup = json_encode($reply_markup);

          $response = Telegram::sendMessage([
              'text' => $string,
              'reply_markup' => $encodedMarkup,
              'chat_id' => $chat_id
          ]);
          $text = false;
        } else {
          $text = '⚠ Сума має бути додатнім числом';
        }
      } else {
        $text = '⚠ Будь-ласка введи ціле число';
      }
      // якщо є повідомлення то виводимо
      if ($text) {
        Telegram::sendMessage([
         'chat_id' => $chat_id,
         'text' => $text
        ]);
      }
    }

    public function confirmPay($state, $chat_id)
    {
      $params = json_decode($state['params']);

      $tutor = Tutor::findorfail($params->id);
      $tutor->balance = $tutor->balance - $params->summ;
      $tutor->save();

      Telegram::sendMessage([
       'chat_id' => $chat_id,
       'text' => '💶 Ми успішно видали заробітну плату!' .PHP_EOL. 'Щасливий тьютор біжить в приприжку 🏃' .PHP_EOL. '☝ Не забувай хвалити працівників!'
      ]);
    }
}
