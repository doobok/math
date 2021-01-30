<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram;

/**
 * Class HelpCommand.
 */
class ReportsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'reports';

    /**
     * @var array Command Aliases
     */
    // protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'звіти по ефективності роботи';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)

    {
        $update = Telegram::getWebhookUpdates();
        $chat_id = $update['message']['from']['id'];

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
            'text' => 'Оберіть тип звіту',
            'reply_markup' => $encodedMarkup,
            'chat_id' => $chat_id
        ]);

    }

}
