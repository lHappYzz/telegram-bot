<?php
namespace Boot\Src;


class Telegram {

    /**
     * @var TelegramRequest
     * TelegramRequest represents an update that comes from telegram
     */
    protected TelegramRequest $request;

    public function __construct() {
        $this->request = new TelegramRequest();
    }

}