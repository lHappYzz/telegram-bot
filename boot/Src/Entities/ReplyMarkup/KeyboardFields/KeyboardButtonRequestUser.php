<?php

namespace Boot\Src\Entities\ReplyMarkup\KeyboardFields;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * This object defines the criteria used to request a suitable user.
 * The identifier of the selected user will be shared with the bot when the corresponding button is pressed.
 * @link https://core.telegram.org/bots/api#keyboardbuttonrequestuser
 */
class KeyboardButtonRequestUser extends JsonSerializableEntity
{
    public function __construct(
        protected int $requestId,
        protected ?bool $userIsBot = null,
        protected ?bool $userIsPremium = null,
    ){}
}