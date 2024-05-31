<?php

namespace Boot\Src\APIMethods;

use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;

class AnswerCallbackQuery extends TelegramMethod
{
    /**
     * @param string $callbackQueryId
     * @param string|null $text
     * @param bool|null $showAlert
     * @param string|null $url
     * @param int|null $cacheTime
     */
    public function __construct(
        public string $callbackQueryId,
        public ?string $text = null,
        public ?bool $showAlert = null,
        public ?string $url = null,
        public ?int $cacheTime = null
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->text !== null && mb_strlen($this->text) > 200) {
            throw new StringLengthExceededException('text', 200, $this->getMethodName());
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'answerCallbackQuery';
    }
}