<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram;

/**
 * Class HelpCommand.
 */
class SheduleCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'shedule';

    /**
     * @var array Command Aliases
     */
    // protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'розклад для тьютора на найближчий час';

    /**
     * {@inheritdoc}
     */
    public function handle($request)

    {

      $update = Telegram::getWebhookUpdates();
      $chat_id = $update['message']['from']['id'];


      $tutors = \App\Tutor::where('active', 1)->get();

      Telegram::sendMessage([
        'chat_id' => $chat_id,
        'text' => 'Оберіть потрібний період'
      ]);

      // Build the list
      foreach ($tutors as $tutor) {
        //  Keyboard
        $keyboard = [
          [
            ['text'=>'🔍 Найближче заняття','callback_data'=>json_encode(['action'=>'show','day'=>'soon','id'=>$tutor->id])],
          ],
          [
            ['text'=>'🌞 Сьогодні','callback_data'=>json_encode(['action'=>'show','day'=>'today','id'=>$tutor->id])],
            ['text'=>'➡ Завтра','callback_data'=>json_encode(['action'=>'show','day'=>'tomorrow','id'=>$tutor->id])],
            ['text'=>'➡➡ Післязавтра','callback_data'=>json_encode(['action'=>'show','day'=>'aftertomorrow','id'=>$tutor->id])],
          ]
        ];
          $text = sprintf('ID%s: %s %s %s' . PHP_EOL, $tutor->id, $tutor->lname, $tutor->name, $tutor->mname );

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

    }
}
