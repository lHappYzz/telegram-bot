<?php

namespace Boot\Src\Abstracts;

use App\Bot;
use Boot\Interfaces\ChatState;
use Boot\Src\Entities\TelegramMessage;

abstract class State implements ChatState
{
    /**
     * @inheritDoc
     */
    abstract public function handle(Bot $bot, TelegramMessage $telegramMessage): void;
}