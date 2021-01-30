<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram;

/**
 * Class HelpCommand.
 */
class StudentsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'students';

    /**
     * @var array Command Aliases
     */
    // protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'інформація про учнів та поповнення балансу';

    /**
     * {@inheritdoc}
     */
    public function handle($request)

    {

      $update = Telegram::getWebhookUpdates();
      $chat_id = $update['message']['from']['id'];

      //  Keyboard
      $keyboard = array(
        array(
           array('text'=>'🔍 Знайти учня','callback_data'=>'{"action":"find"}'),
        )
      );

      $reply_markup = Keyboard::make([
         'inline_keyboard' => $keyboard,
         'resize_keyboard' => true,
         'one_time_keyboard' => true
      ]);
      $encodedMarkup = json_encode($reply_markup);

      $response = Telegram::sendMessage([
          'text' => '🐼 Ох ці учні, давай спробуємо знайти когось! Від нас ніхто не сховається!' . PHP_EOL . 'Натисни на кнопку пошуку...',
          'reply_markup' => $encodedMarkup,
          'chat_id' => $chat_id
      ]);

    }
}
