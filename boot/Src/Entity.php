<?php

namespace Boot\Src;

use App\bot;
use BadMethodCallException;

/**
 * Class Entity
 * @method telegramMessage getMessage() New incoming message
 * @method telegramChat getChat() Chat where the event came from
 * @method CallbackQuery getCallbackQuery() New incoming callback query
 */
abstract class Entity
{
    public function __call(string $methodName, array $arguments): Entity
    {
        if (strpos($methodName, 'get') === 0) {
            return bot::$update->getInstance($methodName);
        }

        throw new BadMethodCallException('Bad method call: ' .
            $methodName .
            '. At ' . __CLASS__ .
            '::' . __METHOD__ .
            '. Line: ' . __LINE__);
    }
}