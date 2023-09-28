<?php

namespace App\States;

use App\Bot;
use Boot\Src\Abstracts\State;
use Boot\Src\Entities\TelegramMessage;

class DefaultState extends State
{
    /**
     * @inheritDoc
     */
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void
    {
        //handle chats with default status
    }
}