<?php

namespace Boot\Src\Abstracts;

use App\Bot;
use Boot\Interfaces\ChatState;
use Boot\Src\Entities\TelegramChat;

abstract class State implements ChatState
{
    protected TelegramChat $telegramChat;

    public function __construct(TelegramChat $telegramChat)
    {
        $this->telegramChat = $telegramChat;
    }

    abstract public function handle(Bot $bot): void;
}