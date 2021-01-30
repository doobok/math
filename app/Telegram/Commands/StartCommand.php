<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram;

/**
 * Class HelpCommand.
 */
class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var array Command Aliases
     */
    // protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'початок роботи - викликає меню розділів';


    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram_user = Telegram::getWebhookUpdates()['message'];
        $chat_id = $telegram_user['from']['id'];

            $keyboard = [
                ['👦 Учні', '🎓 Тьютори'],
                ['🕜 Розклад', '📈 Звіти'],
            ];

            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);
            $encodedMarkup = json_encode($reply_markup);

            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => '🐼 Давай, обирай потрібний розділ!',
                'reply_markup' => $encodedMarkup
            ]);
    }

}
