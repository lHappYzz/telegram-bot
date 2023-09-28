<?php

namespace Boot;

use App\Bot;
use App\InlineQuery\InlineQueryHandler;
use App\States\DefaultState;
use Boot\Interfaces\ContainerInterface;
use Boot\Interfaces\PermissionManager;
use Boot\Src\Abstracts\CallbackQueryHandler;
use Boot\Src\Entities\CallbackQuery;
use Boot\Src\Entities\InlineQuery;
use Boot\Src\Entities\TelegramMessage;
use Boot\Traits\Helpers;

/**
 * This class helps to run the corresponding parts of the application
 * according to the type of update received from the telegram.
 * E.g. CommandHandlers, CallbackQueryHandlers etc
 */
class Responsibilities
{
    use Helpers;

    /**
     * @param Bot $bot
     * @param PermissionManager $manager
     * @param Container $container
     */
    public function __construct(
        private Bot $bot,
        protected PermissionManager $manager,
        protected ContainerInterface $container,
    ) {}

    /**
     * @param TelegramMessage $telegramMessage
     * @return void
     */
    public function handleCommand(TelegramMessage $telegramMessage): void
    {
        if ($this->manager->hasCommandAccess(
            $telegramMessage->getFrom(),
            $telegramMessage->getCommand()
        )) {
            $telegramMessage->getCommand()->boot($this->bot, $telegramMessage);
        }
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return void
     */
    public function handleTelegramChatState(TelegramMessage $telegramMessage): void
    {
        $telegramMessage->getChat()->getChatState()->handle($this->bot, $telegramMessage);
    }

    /**
     * @param CallbackQuery $callbackQuery
     * @return void
     */
    public function handleCallbackQuery(CallbackQuery $callbackQuery): void
    {
        $handler = $this->container->get($this->resolveCallbackQueryHandlerName($callbackQuery->getData()));

        if (!$handler instanceof CallbackQueryHandler) {
            return;
        }

        if (
            $handler->specificChatState === null ||
            $callbackQuery->getChat()->getChatState()::class === $handler->specificChatState
        ) {
            $handler->handle($this->bot, $callbackQuery);
        }
    }

    /**
     * @param InlineQuery $inlineQuery
     * @return void
     */
    public function handleInlineQuery(InlineQuery $inlineQuery): void
    {
        container(InlineQueryHandler::class, ['inlineQuery' => $inlineQuery])->handle();
    }
}