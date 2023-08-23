<?php

namespace Boot\Src\Abstracts;

use Boot\Src\TelegramRequest;

abstract class Telegram
{
    /** @var string */
    public const COMMANDS_NAMESPACE = 'App\\Commands\\';

    /** @var string */
    public const CALLBACK_QUERY_NAMESPACE = 'App\\CallbackQueryHandlers\\';

    /**
     * @param TelegramRequest $request
     */
    public function __construct(protected TelegramRequest $request) {}

    /**
     * @param array $parameters
     * @return void
     */
    public function sendTelegramRequest(array $parameters): void
    {
        $this->request::sendTelegramRequest($parameters);
    }
}