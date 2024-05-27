<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;

class SendMessage extends TelegramMethod
{
    /**
     * @param string $text
     * @param string $chatId
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $disableWebPagePreview
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        public string $text,
        public string $chatId,
        public ?ReplyMarkup $replyMarkup = null,
        public ?string $parseMode = null,
        public bool $disableWebPagePreview = false,
        public ?MethodOptionalFields $optionalFields = null
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if (mb_strlen($this->text) > 4096) {
            throw new StringLengthExceededException('text', 1024, $this->getMethodName());
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'sendMessage';
    }
}