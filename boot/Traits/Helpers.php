<?php

namespace Boot\Traits;

use Boot\Src\Abstracts\CallbackQueryHandler;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardButton;
use Boot\Src\Abstracts\Telegram;

trait Helpers
{
    /**
     * @param string $callbackData
     * @return string
     */
    private function resolveCallbackQueryHandlerName(string $callbackData): string
    {
        return Telegram::CALLBACK_QUERY_NAMESPACE . array_first(explode(InlineKeyboardButton::CALLBACK_DATA_DELIMITER, $callbackData)) .
            CallbackQueryHandler::CALLBACK_QUERY_HANDLERS_ENDING;
    }
}