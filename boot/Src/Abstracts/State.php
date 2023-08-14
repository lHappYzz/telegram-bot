<?php

namespace Boot\Src\Abstracts;

use App\Bot;
use Boot\Interfaces\ChatState;
use Boot\Src\Entities\TelegramChat;
use Boot\Src\Entities\TelegramMessage;

abstract class State implements ChatState
{
    protected TelegramChat $telegramChat;

    public function __construct(TelegramChat $telegramChat)
    {
        $this->telegramChat = $telegramChat;
    }

    abstract public function handle(Bot $bot, TelegramMessage $telegramMessage): void;
}