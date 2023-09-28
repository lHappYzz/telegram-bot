<?php

namespace Boot;

use App\Bot;
use App\Config\Config;
use App\Config\ContainerConfig;
use Boot\Interfaces\ContainerInterface;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Abstracts\Telegram;
use Boot\Src\Entities\MessageEntity;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardButton;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardMarkup;
use Boot\Src\Entities\TelegramMessage;
use Boot\Src\Exceptions\ContainerException;
use Boot\Src\PhotoSize;
use Boot\Src\TelegramRequest;
use Boot\Traits\DirectoryHelpers;
use Boot\Traits\Helpers;
use Exception;
use RuntimeException;

class Application
{
    use Helpers;
    use DirectoryHelpers;

    /** @var Bot */
    public Bot $bot;

    /** @var TelegramRequest */
    protected TelegramRequest $telegramRequest;

    /** @var self */
    protected static self $instance;

    /**
     * @param Container $container
     */
    public function __construct(protected Container $container)
    {
        if (!Config::exists()) {
            throw new RuntimeException('Missing application configuration file.');
        }

        if (!Config::bot()['bot_token']) {
            throw new RuntimeException('Missing bot token.');
        }

        date_default_timezone_set(Config::timezone() ?? '');

        $this->registerBaseBindings();

        $this->telegramRequest = $this->container->get(TelegramRequest::class);

        $this->bot = $this->container->get(Bot::class);
    }

    /**
     * Entry point of the application. Launches application components according to received telegram Update
     *
     * @throws Exception
     */
    public function boot(): void
    {
        $this
            ->telegramRequest
            ->getUpdate()
            ->updateUnit
            ->responsibilize($this->container->get(Responsibilities::class));
    }

    /**
     * Runs a command from any place of the application
     *
     * @param string $signatureOrFullName
     * @param TelegramMessage $telegramMessage
     * @param array $parameters
     * @return void
     */
    public static function bootCommand(string $signatureOrFullName, TelegramMessage $telegramMessage, array $parameters = []): void
    {
        $instance = self::$instance->getCommand($signatureOrFullName);

        $instance?->boot(self::$instance->bot, $telegramMessage, $parameters);
    }

    /**
     * @param string $signatureOrFullName
     * @return BaseCommand|null
     */
    public function getCommand(string $signatureOrFullName): ?BaseCommand
    {
        try {
            $command = container($signatureOrFullName);

            if ($command instanceof BaseCommand) {
                return $command;
            }
        } catch (ContainerException) {
            foreach ($this->getFiles('app' . DIRECTORY_SEPARATOR . 'Commands') as $file) {
                /** @var BaseCommand $command */
                $command = container(Telegram::COMMANDS_NAMESPACE . substr($file, 0, -4));

                if ($command->getSignature() === $signatureOrFullName) {
                    return $command;
                }
            }
        }

        return null;
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
        $this->registerTelegramEntitiesBindings();
    }

    /**
     * @return void
     */
    protected function registerTelegramEntitiesBindings(): void
    {
        $this
            ->container
            ->when(TelegramMessage::class)
            ->needs('photo')
            ->give(function (array $data) {
                $result = [];
                foreach ($data as $photoSizeData) {
                    $result[] = container(PhotoSize::class, $photoSizeData);
                }
                return $result;
            });

        $this
            ->container
            ->when(TelegramMessage::class)
            ->needs('replyMarkup')
            ->give(function (array $inlineKeyboardMarkupData) {
                /** @var InlineKeyboardMarkup $inlineKeyboardMarkup */
                $inlineKeyboardMarkup = container(InlineKeyboardMarkup::class);

                foreach ($inlineKeyboardMarkupData['inline_keyboard'] as $keyboardRow) {
                    $inlineKeyboardRow = $inlineKeyboardMarkup->addKeyboardRow();
                    foreach ($keyboardRow as $rowButton) {
                        $inlineKeyboardRow
                            ->addButton($rowButton['text'])
                            ->addCallbackHandler(
                                $this->resolveCallbackQueryHandlerName($rowButton['callback_data']),
                                array_last(
                                    explode(
                                        InlineKeyboardButton::CALLBACK_DATA_DELIMITER,
                                        $rowButton['callback_data']
                                    )
                                )
                            );
                    }
                }

                return $inlineKeyboardMarkup;
            });

        $this
            ->container
            ->when(TelegramMessage::class)
            ->needs('entities')
            ->give(function (array $entitiesFromTelegramRequest) {
                $result = [];

                foreach ($entitiesFromTelegramRequest as $item) {
                    $result[] = container(MessageEntity::class, $item);
                }

                return $result;
            });
    }
}