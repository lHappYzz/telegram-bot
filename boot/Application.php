<?php

namespace Boot;

use App\Bot;
use App\Config\Config;
use App\Config\ContainerConfig;
use Boot\Interfaces\ContainerInterface;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Entities\TelegramMessage;
use Boot\Src\TelegramRequest;
use Exception;
use RuntimeException;

class Application
{
    /** @var Bot */
    protected Bot $bot;

    /** @var TelegramRequest */
    protected TelegramRequest $telegramRequest;

    /** @var self */
    protected static self $instance;

    /**
     * @param Container $container
     */
    public function __construct(protected Container $container)
    {
        $this->registerBaseBindings();

        $this->telegramRequest = $this->container->get(TelegramRequest::class);

        $this->bot = $this->container->get(Bot::class);
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
            ->telegramRequest
            ->getUpdate()
            ->updateUnit
            ->responsibilize($this->container->get(Responsibilities::class));
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
        $instance = self::$instance->container->get($commandName);

        if ($instance instanceof BaseCommand) {
            $instance->boot(self::$instance->bot, $telegramMessage, $parameters);
        }
    }

    /**
     * @return void
     */
    protected function registerBaseBindings(): void
    {
        self::$instance = $this;
        $this->container->singleton(self::class, $this);
        $this->container->singleton(ContainerInterface::class, $this->container);
        $this->container->singleton(Container::class, $this->container);

        $this->container->singleton(TelegramRequest::class);

        $this->container->get(ContainerConfig::class)->bindings();
    }
}