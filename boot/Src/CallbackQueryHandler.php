<?php

namespace Boot\Src;

use App\Bot;

abstract class CallbackQueryHandler extends Singleton
{
    public const CALLBACK_QUERY_HANDLERS_ENDING = 'Handler';

    abstract public function handle(Bot $bot, CallbackQuery $callbackQuery): void;
}