<?php

namespace Boot\Src\Abstracts;

use App\Bot;
use Boot\Src\Entities\CallbackQuery;

abstract class CallbackQueryHandler extends Singleton
{
    /**
     * May contain fully qualified chat state name if you want to allow access to this handler only if specific chat state is set
     * It is null if the handler can be accessed regardless of the chat state
     *
     * @var string|null
     */
    public ?string $specificChatState = null;

    public const CALLBACK_QUERY_HANDLERS_ENDING = 'Handler';

    abstract public function handle(Bot $bot, CallbackQuery $callbackQuery): void;
}