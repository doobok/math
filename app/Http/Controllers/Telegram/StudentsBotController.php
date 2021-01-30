<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram;
use App\Student;
use Telegram\Bot\Keyboard\Keyboard;

class StudentsBotController extends Controller
{
    // обробляємо колбеки
    public function callBacks($callback, $state, $chat_id)
    {
      switch ($state['action']) {
        case 'find':
        // третя дія (запит суми поповнення)
          $state->update([
            'action' => 'pay',
            'params' => json_encode(['id' => $callback['callback_query']['data']])
          ]);
          Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => '💲 Введи суму поповнення'
          ]);
          break;
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
          $state->update(['command' => '/students','action' => 'find','params' => null]);//обнуляємо стейт
            Telegram::sendMessage([
              'chat_id' => $chat_id,
              'text' => '🐼 Давай ще когось знайдемо! Це так весело! Просто введи нове прізвище'
            ]);
          break;

        default:
        // перша дія (повідомлення з поясненням)
          $state->update([
            'action' => 'find'
          ]);
          Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Для того щоб розпочати пошук, введи прізвище учня, або кілька літер з яких воно починається, це просто. Але памʼятай, за раз я можу вивести не більше 5 учнів!'
          ]);
          break;
      }
      // $string = $update['callback_query']['data'];

      // $text = sprintf('%s: %s, %s: %s'.PHP_EOL, 'Callback', $callback['callback_query']['data'], 'state', $state);
      //   Telegram::sendMessage([
      //     'chat_id' => $chat_id,
      //     'text' => $text
      //   ]);
    }

    // обробляємо звичайний ввід
    public function textInputs($input, $state, $chat_id)
    {
      switch ($state['action']) {
        // друга дія (шукаємо учня по фразі)
        case 'find':
            return self::findStudents($input, $state, $chat_id);
          break;
        // четверта дія (ввід суми)
        case 'pay':
            return self::inputPay($input, $state, $chat_id);
        $text = sprintf('%s: %s'.PHP_EOL, 'Ви ввели', $input);
          Telegram::sendMessage([
           'chat_id' => $chat_id,
           'text' => $text
          ]);
          break;

        default:
            Telegram::sendMessage([
             'chat_id' => $chat_id,
             'text' => '🐼 Я тебе не розумію.' .PHP_EOL.'Можливо, очікується натиснення на кнопку?' .PHP_EOL.'А інколи варто почати з команди /start'
            ]);
          break;
      }

    }

    //////////////////////////////////////////////////////////
    // службові функції
    public function findStudents($input, $state, $chat_id)
    {
      $students = Student::where('active', 1)->where('lname', 'like', $input . '%')->limit(5)->get();
      if ($students->count() > 0) {
        $text = sprintf('%s: %s'.PHP_EOL, '🐼 Я знайшов відповідностей', $students->count());
        Telegram::sendMessage([
         'chat_id' => $chat_id,
         'text' => $text
        ]);

        foreach ($students as $student) {
          $keyboard = [
            [
              ['text'=>'💵 Поповнити баланс','callback_data'=>$student->id],
            ],
          ];
          $string = sprintf('ID%s: 👦 %s %s. %s: %s' . PHP_EOL, $student->id, $student->lname, $student->name, '💵 Баланс', $student->balance );

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
        }

        Telegram::sendMessage([
         'chat_id' => $chat_id,
         'text' => '💁 Щоб знайти інших учнів просто напиши новий запит'
        ]);
      } else {
        Telegram::sendMessage([
         'chat_id' => $chat_id,
         'text' => '👉 Шкода, але я не зміг нікого знайти, спробуй змінити пошуковий запит'
        ]);
      }
    }

    public function inputPay($input, $state, $chat_id)
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
          $student = Student::findorfail($params->id);
          // формуємо відповідь
          $keyboard = [
            [
              ['text'=>'✅ Підтвердити','callback_data'=>'confirm'],
              ['text'=>'🚫 Скасувати','callback_data'=>'cancel'],
            ],
          ];
          $string = sprintf('%s:%s' . PHP_EOL, '❓ Поповнити баланс учня з ID', $student->id );
          $string .= sprintf('%s %s, %s %s %s' . PHP_EOL, $student->lname, $student->name, 'на 💵', $input, 'грн?' );

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

      $student = Student::findorfail($params->id);
      $student->balance = $student->balance + $params->summ;
      $student->save();

      Telegram::sendMessage([
       'chat_id' => $chat_id,
       'text' => '💶 Баланс успішно поповнено'
      ]);
    }
}
