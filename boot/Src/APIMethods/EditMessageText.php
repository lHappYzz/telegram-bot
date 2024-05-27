<?php

namespace Boot\Src\APIMethods;

use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;

class EditMessageText extends TelegramMethod
{
    /**
     * @param string $text
     * @param string $chatId
     * @param int $messageId
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $disableWebPagePreview
     * @param array|null $entities
     */
    public function __construct(
        public string $text,
        public string $chatId,
        public int $messageId,
        public ?ReplyMarkup $replyMarkup = null,
        public ?string $parseMode = null,
        public bool $disableWebPagePreview = false,
        public ?array $entities = null
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->text !== null && mb_strlen($this->text) > 4096) {
            throw new StringLengthExceededException('text', 4096, $this->getMethodName());
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'editMessageText';
    }
}