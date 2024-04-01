<?php

namespace Boot\Src\Entities\ReplyMarkup\KeyboardFields;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * This object represents type of a poll, which is allowed to be created and sent when the corresponding button is pressed.
 * @link https://core.telegram.org/bots/api#keyboardbuttonpolltype
 */
class KeyboardButtonPollType extends JsonSerializableEntity
{
    /** @var string */
    public const QUIZ_POLL_TYPE = 'quiz';

    /** @var string */
    public const REGULAR_POLL_TYPE = 'regular';

    /**
     * If quiz is passed, the user will be allowed to create only polls in the quiz mode.
     * If regular is passed, only regular polls will be allowed. Otherwise, the user will be allowed to create a poll of any type.
     *
     * @param string $type
     */
    public function __construct(public string $type) {}
}