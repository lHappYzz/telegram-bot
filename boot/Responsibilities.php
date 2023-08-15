<?php

namespace Boot;

use App\Bot;
use App\States\DefaultState;
use Boot\Src\Abstracts\CallbackQueryHandler;
use Boot\Src\Entities\CallbackQuery;
use Boot\Src\Entities\TelegramMessage;
use Boot\Traits\DirectoryHelpers;
use Boot\Traits\Helpers;

/**
 * This class helps to run the corresponding parts of the application
 * according to the type of update received from the telegram.
 * E.g. CommandHandlers, CallbackQueryHandlers etc
 */
class Responsibilities
{
    use Helpers, DirectoryHelpers;

    /**
     * @param Bot $bot
     */
    public function __construct(private Bot $bot) {}

    /**
     * @param TelegramMessage $telegramMessage
     * @return void
     */
    public function handleCommand(TelegramMessage $telegramMessage): void
    {
        Application::bootCommand($telegramMessage->getCommandClassName(), $telegramMessage);
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return void
     */
    public function handleTelegramChatState(TelegramMessage $telegramMessage): void
    {
        if (!$telegramMessage->getChat()->getChatState() instanceof DefaultState) {
            $telegramMessage->getChat()->getChatState()->handle($this->bot, $telegramMessage);
        }
    }

    /**
     * @param CallbackQuery $callbackQuery
     * @return void
     */
    public function handleCallbackQuery(CallbackQuery $callbackQuery): void
    {
        $handler = $this->getClassInstance($this->resolveCallbackQueryHandlerName($callbackQuery->getData()));

        if (!$handler instanceof CallbackQueryHandler) {
            return;
        }

        if ($handler->specificChatState === null) {
            $handler->handle($this->bot, $callbackQuery);
        } elseif ($callbackQuery->getChat()->getChatState()::class === $handler->specificChatState) {
            $handler->handle($this->bot, $callbackQuery);
        }
    }
}