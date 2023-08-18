<?php

namespace Boot\Traits;

use Boot\Src\Abstracts\CallbackQueryHandler;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardButton;
use Boot\Src\Abstracts\Telegram;

trait Helpers
{
    private function resolveCallbackQueryHandlerName(string $callbackData): string
    {
        return  Telegram::CALLBACK_QUERY_NAMESPACE.$this->arrayFirst(explode(InlineKeyboardButton::CALLBACK_DATA_DELIMITER, $callbackData)) .
            CallbackQueryHandler::CALLBACK_QUERY_HANDLERS_ENDING;
    }

    /**
     * Convert string camel case style to snake case
     * @param string $input
     * @return string
     */
    private function camelCaseToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * Convert string snake case style to camel case
     * @param string $input
     * @return string
     */
    private function snakeCaseToCamelCase(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }

    private function arrayFirst(array $array)
    {
        if (($firstElement = reset($array)) === false) {
            return '';
        }
        return $firstElement;
    }

    private function arrayLast(array $array)
    {
        if (($lastElement = end($array)) === false) {
            return '';
        }
        return $lastElement;
    }
}