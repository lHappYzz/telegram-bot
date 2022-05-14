<?php

namespace Boot\Src\Abstracts;

use App\Bot;
use Boot\Src\Entities\CallbackQuery;

abstract class CallbackQueryHandler extends Singleton
{
    public const CALLBACK_QUERY_HANDLERS_ENDING = 'Handler';

    abstract public function handle(Bot $bot, CallbackQuery $callbackQuery): void;
}