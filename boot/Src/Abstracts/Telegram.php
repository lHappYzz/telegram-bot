<?php

namespace Boot\Src\Abstracts;

use Boot\Src\TelegramRequest;

abstract class Telegram
{
    public const COMMANDS_NAMESPACE = '\\App\\Commands\\';

    public const CALLBACK_QUERY_NAMESPACE = '\\App\\CallbackQueryHandlers\\';

    /**
     * @var TelegramRequest
     * telegramRequest represents an update that comes from telegram
     */
    public TelegramRequest $request;

    public function __construct()
    {
        $this->request = new TelegramRequest();
    }
}