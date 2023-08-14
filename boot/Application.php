<?php

namespace Boot;

use App\Bot;
use App\Commands\BaseCommand;
use App\Config\Config;
use Boot\Facades\TelegramFacade;
use Boot\Src\Abstracts\Singleton;
use Boot\Src\Entities\TelegramMessage;
use Boot\Traits\DirectoryHelpers;
use Boot\Traits\Helpers;
use Exception;

class Application extends Singleton
{
    use Helpers, DirectoryHelpers;

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
     * Starts the application by doing some things like getting the configuration or parsing telegram request
     * In success case new bot instance will be returned else an exception will be thrown
     *
     * @throws Exception
     */
    public function boot(): void
    {
        if (!Config::exists()) {
            throw new Exception('Missing application configuration file');
        }

        date_default_timezone_set(Config::timezone());

        $this
            ->telegramFacade
            ->getUpdate()
            ->updateUnit
            ->responsibilize(new Responsibilities($this->bot));
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