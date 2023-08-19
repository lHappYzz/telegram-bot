<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * This object represents an inline button that switches the current user to inline mode in a chosen chat,
 * with an optional default inline query.
 * @link https://core.telegram.org/bots/api#switchinlinequerychosenchat
 */
class SwitchInlineQueryChosenChat extends JsonSerializableEntity
{
    /**
     * @param string|null $query
     * @param bool|null $allowUserChats
     * @param bool|null $allowBotChats
     * @param bool|null $allowGroupChats
     * @param bool|null $allowChannelChats
     */
    public function __construct(
        public ?string $query = null,
        public ?bool $allowUserChats = null,
        public ?bool $allowBotChats = null,
        public ?bool $allowGroupChats = null,
        public ?bool $allowChannelChats = null,
    ) {}
}