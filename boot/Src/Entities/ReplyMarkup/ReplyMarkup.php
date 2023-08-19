<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * Additional interface options.
 * A JSON-serialized object for an inline keyboard,
 * custom reply keyboard, instructions to remove reply keyboard or
 * to force a reply from the user.
 * @link https://core.telegram.org/bots/api#callbackquery
 */
abstract class ReplyMarkup extends JsonSerializableEntity
{
    //
}