<?php

namespace App\States;

use App\Bot;
use Boot\Src\Abstracts\State;
use Boot\Src\Entities\TelegramMessage;

class NoState extends State
{
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void
    {
        //handle chats with no status (more likely this user has never before used this bot)
    }
}