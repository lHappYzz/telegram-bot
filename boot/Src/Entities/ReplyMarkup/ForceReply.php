<?php

namespace Boot\Src\Entities\ReplyMarkup;

/**
 * Upon receiving a message with this object, Telegram clients will display a reply interface to the user
 * (act as if the user has selected the bot's message and tapped 'Reply').
 */
class ForceReply extends ReplyMarkup
{
    /** @var bool */
    protected bool $forceReply = true;

    /**
     * @param string|null $inputFieldPlaceholder
     * @param bool|null $selective
     */
    public function __construct(
        protected ?string $inputFieldPlaceholder = null,
        protected ?bool $selective = null,
    ) {}

}