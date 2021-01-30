<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'help';

    /**
     * @var array Command Aliases
     */
    // protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'довідка';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)

    {
        $this->replyWithMessage(['text' => '🐼 Привіт! Давай знайомитись! Мене звати Панда-Трофим, я допомагатиму тобі працювати з твоєю роботою. Тільки мої можливості не безмежні. Наразі я вмію виконувати наступні команди:']);

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $commands = $this->getTelegram()->getCommands();

        $response = '';
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }
        $this->replyWithMessage(['text' => $response]);

        // $response = $this->getUpdate();
        //
        // $text = 'Привіт! Дякую що завітали.'.chr(10).chr(10);
        // $text .= 'Я бот, який працює для'.chr(10);
        // $text .= env('APP_URL').chr(10).chr(10);
        // $text .= 'Для того щоб ознайомитись із нашою діяльністю відвідайте наш сайт.'.chr(10);
        //
        // $this->replyWithMessage(compact('text'));

    }
}
