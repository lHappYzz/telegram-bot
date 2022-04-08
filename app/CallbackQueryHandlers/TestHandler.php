<?php

namespace App\CallbackQueryHandlers;

use App\bot;
use Boot\Src\CallbackQuery;
use Boot\Src\CallbackQueryHandler;

class TestHandler extends CallbackQueryHandler
{
    public function handle(bot $bot, CallbackQuery $callbackQuery): void
    {
        $bot->sendMessage($callbackQuery->getData(), $callbackQuery->getMessage()->getChat());
    }
}