<?php

namespace Boot\Src;

abstract class telegram {

    /**
     * @var telegramRequest
     * telegramRequest represents an update that comes from telegram
     */
    public telegramRequest $request;

    public function __construct() {
        $this->request = new telegramRequest();
    }
}