<?php

namespace Boot\Src\Abstracts;

use App\Bot;
use Boot\Interfaces\ChatState;
use Boot\Src\Entities\TelegramMessage;

abstract class State implements ChatState
{
    /**
     * Invoked when a new Update of type Message is received
     *
     * @param Bot $bot
     * @param TelegramMessage $telegramMessage
     * @return void
     */
    abstract public function handle(Bot $bot, TelegramMessage $telegramMessage): void;
}