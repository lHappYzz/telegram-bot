<?php

namespace App\States;

use App\Bot;
use Boot\Src\Abstracts\State;
use Boot\Src\Entities\TelegramMessage;

class NoState extends State
{
    /**
     * @inheritDoc
     */
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void
    {
        //handle chats with no status (State for chats that is not present in DB)
    }
}