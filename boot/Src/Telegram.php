<?php

namespace Boot\Src;

abstract class Telegram
{
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