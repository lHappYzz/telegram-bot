<?php

namespace Boot\Interfaces;

use App\Bot;
use Boot\Src\Entities\TelegramMessage;

interface ChatState
{
    /**
     * Invoked when a new Update of type Message is received
     *
     * @param Bot $bot
     * @param TelegramMessage $telegramMessage
     * @return void
     */
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void;
}