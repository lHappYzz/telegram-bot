<?php

namespace Boot\Src\Abstracts;

use Boot\Src\TelegramRequest;

abstract class Telegram
{
    /** @var string */
    public const string COMMANDS_NAMESPACE = 'App\\Commands\\';

    /** @var string */
    public const string CALLBACK_QUERY_NAMESPACE = 'App\\CallbackQueryHandlers\\';

    /**
     * @param TelegramRequest $request
     */
    public function __construct(protected TelegramRequest $request) {}
}