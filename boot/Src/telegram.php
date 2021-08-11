<?php
namespace Boot\Src;


class telegram {

    /**
     * @var telegramRequest
     * telegramRequest represents an update that comes from telegram
     */
    protected telegramRequest $request;

    public function __construct() {
        $this->request = new telegramRequest();
    }

}