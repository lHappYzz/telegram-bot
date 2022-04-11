<?php

namespace Boot\Src;

use App\bot;

abstract class CallbackQueryHandler extends singleton
{
    abstract public function handle(bot $bot, CallbackQuery $callbackQuery): void;
}