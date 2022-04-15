<?php

namespace Boot\Src;

use App\Bot;

abstract class CallbackQueryHandler extends Singleton
{
    abstract public function handle(Bot $bot, CallbackQuery $callbackQuery): void;
}