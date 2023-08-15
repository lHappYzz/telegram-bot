<?php

namespace Boot\Src\Abstracts;

use Boot\Src\TelegramRequest;
use Boot\Src\Update;
use Boot\TelegramUpdateParser;

abstract class Telegram
{
    public const COMMANDS_NAMESPACE = '\\App\\Commands\\';

    public const CALLBACK_QUERY_NAMESPACE = '\\App\\CallbackQueryHandlers\\';

    /**
     * TelegramRequest represents an update that comes from telegram on a webhook
     *
     * @var TelegramRequest
     */
    protected TelegramRequest $request;

    public function __construct(protected TelegramUpdateParser $telegramUpdateParser)
    {
        $this->request = new TelegramRequest($telegramUpdateParser);
    }

    /**
     * @param array $parameters
     * @return void
     */
    public function sendTelegramRequest(array $parameters): void
    {
        $this->request::sendTelegramRequest($parameters);
    }

    /**
     * @return Update
     */
    public function getUpdate(): Update
    {
        return $this->request->update;
    }
}