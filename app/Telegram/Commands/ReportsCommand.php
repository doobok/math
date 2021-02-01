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

        // викликаємо меню з контролера
        $res = new \App\Http\Controllers\Telegram\ReportsBotController();
        $res->mainMenu($chat_id);

    }

}
