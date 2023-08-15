<?php

namespace Boot\Interfaces;

use App\Bot;
use Boot\Src\Entities\TelegramMessage;

interface ChatState
{
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void;
}