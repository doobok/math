<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram;

/**
 * Class HelpCommand.
 */
class TutorsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'tutors';

    /**
     * @var array Command Aliases
     */
    // protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'інформація про тьюторів та виплата ЗП';

    /**
     * {@inheritdoc}
     */
    public function handle($request)

    {

      $update = Telegram::getWebhookUpdates();
      $chat_id = $update['message']['from']['id'];


      $tutors = \App\Tutor::where('active', 1)->get();

      // Build the list
      foreach ($tutors as $tutor) {
        //  Keyboard
        $keyboard = [
          [
             ['text'=>'📜 Переглянути інформацію','callback_data'=>json_encode(['action'=>'show','id'=>$tutor->id])],
             ['text'=>'💵 Видати ЗП','callback_data'=>json_encode(['action'=>'pay','id'=>$tutor->id])],
          ]
        ];
          $text = sprintf('ID%s: %s %s %s' . PHP_EOL, $tutor->id, $tutor->lname, $tutor->name, $tutor->mname );
          $text .= sprintf('💵 баланс: %s грн.' . PHP_EOL, $tutor->balance );

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
