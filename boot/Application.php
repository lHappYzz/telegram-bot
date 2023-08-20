<?php

namespace Boot;

use App\Bot;
use App\Config\Config;
use Boot\Facades\TelegramFacade;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Abstracts\Singleton;
use Boot\Src\Entities\TelegramMessage;
use Boot\Traits\DirectoryHelpers;
use Exception;
use RuntimeException;

class Application extends Singleton
{
    use DirectoryHelpers;

    /** @var Bot */
    private Bot $bot;

    /** @var TelegramFacade */
    private TelegramFacade $telegramFacade;

    public function __construct()
    {
        parent::__construct();
        $this->telegramFacade = new TelegramFacade(new TelegramUpdateParser());
        $this->bot = new Bot($this->telegramFacade);
    }

    /**
     * Entry point of the application. Ensures if the config file exists, sets server timezone
     * launches application components according to received telegram Update
     *
     * @throws Exception
     */
    public function boot(): void
    {
        if (!Config::exists()) {
            throw new RuntimeException('Missing application configuration file.');
        }

        if (!Config::bot()['bot_token']) {
            throw new RuntimeException('Missing bot token.');
        }

        date_default_timezone_set(Config::timezone());

        $this
            ->telegramFacade
            ->getUpdate()
            ->updateUnit
            ->responsibilize(new Responsibilities($this->bot, new Gate()));
    }

    /**
     * Runs a command from any place of the application
     *
     * @param string $commandName
     * @param TelegramMessage $telegramMessage
     * @param array $parameters
     * @return void
     */
    public static function bootCommand(string $commandName, TelegramMessage $telegramMessage, array $parameters = []): void
    {
        /** @var BaseCommand $instance */
        $instance = self::getInstance()->getClassInstance($commandName);

        if ($instance instanceof BaseCommand) {
            $instance->boot(self::getInstance()->bot, $telegramMessage, $parameters);
        }
    }
}