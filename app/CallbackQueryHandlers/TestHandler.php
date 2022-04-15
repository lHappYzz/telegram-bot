<?php

namespace App\CallbackQueryHandlers;

use App\Bot;
use Boot\Src\CallbackQuery;
use Boot\Src\CallbackQueryHandler;

class TestHandler extends CallbackQueryHandler
{
    public function handle(Bot $bot, CallbackQuery $callbackQuery): void
    {
        $bot->sendMessage($callbackQuery->getData(), $callbackQuery->getMessage()->getChat());
    }
}