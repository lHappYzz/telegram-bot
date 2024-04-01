<?php

namespace Boot\Classes;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

class MethodOptionalFields implements JsonSerializable
{
    /**
     * @param bool $disableNotification
     * Sends the message silently. Users will receive a notification with no sound.
     * @param bool $protectContent
     * Protects the contents of the sent message from forwarding and saving
     * @param string|null $replyToMessageId
     * If the message is a reply, ID of the original message
     * @param bool $allowSendingWithoutReply
     * Pass True if the message should be sent even if the specified replied-to message is not found
     */
    public function __construct(
        protected bool $disableNotification = false,
        protected bool $protectContent = false,
        protected ?string $replyToMessageId = null,
        protected bool $allowSendingWithoutReply = false
    ) {}

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'disable_notification' => "bool",
        'protect_content' => "bool",
        'reply_to_message_id' => "null|string",
        'allow_sending_without_reply' => "bool"
    ])] public function jsonSerialize(): array
    {
        return [
            'disable_notification' => $this->disableNotification,
            'protect_content' => $this->protectContent,
            'reply_to_message_id' => $this->replyToMessageId,
            'allow_sending_without_reply' => $this->allowSendingWithoutReply,
        ];
    }
}