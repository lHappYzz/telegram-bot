<?php

namespace Boot\Src\ReplyMarkup;

/**
 * Additional interface options.
 * A JSON-serialized object for an inline keyboard,
 * custom reply keyboard, instructions to remove reply keyboard or
 * to force a reply from the user.
 * @link https://core.telegram.org/bots/api#callbackquery
 */
abstract class ReplyMarkup
{
    /**
     * Collect all the necessary, according to the telegram API documentation, class fields into an array
     * @return array
     */
    abstract protected function toArray(): array;
}