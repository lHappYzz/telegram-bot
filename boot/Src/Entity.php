<?php

namespace Boot\Src;

use App\Bot;
use BadMethodCallException;

/**
 * Class Entity
 * @method TelegramMessage getMessage() New incoming message
 * @method TelegramChat getChat() Chat where the event came from
 * @method CallbackQuery getCallbackQuery() New incoming callback query
 */
abstract class Entity
{
    public function __call(string $methodName, array $arguments): Entity
    {
        if (str_starts_with($methodName, 'get')) {
            return Bot::$update->getInstance($methodName);
        }

        throw new BadMethodCallException('Bad method call: ' .
            $methodName .
            '. At ' . __CLASS__ .
            '::' . __METHOD__ .
            '. Line: ' . __LINE__);
    }
}