<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram;
use App\Option;
use App\TelegramState;
use App\Http\Controllers\Telegram\StudentsBotController;
use App\Http\Controllers\Telegram\TutorsBotController;
use App\Http\Controllers\Telegram\SheduleBotController;
use App\Http\Controllers\Telegram\ReportsBotController;

class BotController extends Controller
{
    public function __construct()
    {
      $update = Telegram::getWebhookUpdates();
      $admin_id = Option::where('name', 'telegram_admin_id')->pluck('value')->first();
      if ($update->isType('message')) {
        $chat_id = $update['message']['from']['id'];
      } elseif ($update->isType('callback_query')) {
        $chat_id = $update['callback_query']['from']['id'];
      }
      // задаємо глобальний ідентифікатор чату
      $this->chat_id = $chat_id;
      // задаємо стан сесії
      $state = TelegramState::where('userId', $this->chat_id)->first();
      if ($state) {
        $this->state = $state;
      } else {
        $this->state = TelegramState::create([
          'userId' => $chat_id,
        ]);
      }
      // якщо користувач не адмін нічого не робимо
      if ($chat_id != $admin_id) {
        die;
      }
    }

    // приймаємо дані
    public function host()
    {
      $update = Telegram::commandsHandler(true);
        // визначаємо тип відповіді
        if ($update->isType('message')) {
          return self::getMessage($update);
        } elseif ($update->isType('callback_query')) {
          return self::getCallback($update);
        }

    }

    // обробляємо текстові повідомлення
    public function getMessage($update)
    {
        switch($update['message']['text']){
        //
          case '👦 Учні': {
            $this->state->update(['command'=>'/students','action'=>null,'params'=>null]); //вручну обнуляємо стейт
            Telegram::triggerCommand('students', $update);
            break;
          }
          case '🎓 Тьютори': {
            $this->state->update(['command'=>'/tutors','action'=>null,'params'=>null]); //вручну обнуляємо стейт
            Telegram::triggerCommand('tutors', $update);
            break;
          }
          case '🕜 Розклад': {
            $this->state->update(['command'=>'/shedule','action'=>null,'params'=>null]); //вручну обнуляємо стейт
            Telegram::triggerCommand('shedule', $update);
            break;
          }
          case '📈 Звіти': {
            $this->state->update(['command'=>'/reports','action'=>null,'params'=>null]); //вручну обнуляємо стейт
            Telegram::triggerCommand('reports', $update);
            break;
          }
          default: {
            // перевіряємо чи це не команда
            if ($update['message']['text'][0] != '/') {
              // визначаємо зону для вводу і відправляємо у відповідний контролер
              switch ($this->state->command) {
                case '/students':
                  $res = new StudentsBotController();
                  $res->textInputs($update['message']['text'], $this->state, $this->chat_id);
                  break;
                case '/tutors':
                  $res = new TutorsBotController();
                  $res->textInputs($update['message']['text'], $this->state, $this->chat_id);
                  break;
                case '/shedule':
                  $res = new SheduleBotController();
                  $res->textInputs($update['message']['text'], $this->state, $this->chat_id);
                  break;
                case '/reports':
                  $res = new ReportsBotController();
                  $res->textInputs($update['message']['text'], $this->state, $this->chat_id);
                  break;

                default:
                  // code...
                  break;
              }
            } else {
              // якщо команда - обнуляємо сесію
              $this->state->update([
                'command' => $update['message']['text'],
                'action' => null,
                'params' => null,
              ]);
            }
            break;
          }
        }
    }

    // обробляємо колбеки
    public function getCallback($update)
    {
      // визначаємо зону колбека і відправляємо у відповідний контролер
      switch ($this->state->command) {
        case '/students':
          $res = new StudentsBotController();
          $res->callBacks($update, $this->state, $this->chat_id);
          break;
        case '/tutors':
          $res = new TutorsBotController();
          $res->callBacks($update, $this->state, $this->chat_id);
          break;
        case '/shedule':
          $res = new SheduleBotController();
          $res->callBacks($update, $this->state, $this->chat_id);
          break;
        case '/reports':
          $res = new ReportsBotController();
          $res->callBacks($update, $this->state, $this->chat_id);
          break;

        default:
          // code...
          break;
      }
      // повертаємо повідомлення про успішне отримання колбека
      Telegram::answerCallbackQuery([
            'callback_query_id' => $update['callback_query']['id']
        ]);
    }

}
