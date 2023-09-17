<?php

namespace Boot\Src\Entities\InlineMode\InputMessageContent;

use Boot\Src\Entities\MessageEntity;

/**
 * @link https://core.telegram.org/bots/api#inputtextmessagecontent
 */
class InputTextMessageContent extends InputMessageContent
{
    /**
     * @param string $messageText
     * @param string|null $parseMode
     * @param MessageEntity[]|null $entities
     * @param bool|null $disableWebPagePreview
     */
    public function __construct(
        protected string $messageText,
        protected ?string $parseMode = null,
        protected ?array $entities = null,
        protected ?bool $disableWebPagePreview = null,
    ) {}

    /**
     * @param string $parseMode
     * @return InputTextMessageContent
     */
    public function setParseMode(string $parseMode): self
    {
        $this->parseMode = $parseMode;

        return $this;
    }

    /**
     * @param MessageEntity $messageEntity
     * @return InputTextMessageContent
     */
    public function addMessageEntity(MessageEntity $messageEntity): self
    {
        $this->entities[] = $messageEntity;

        return $this;
    }

    /**
     * @return InputTextMessageContent
     */
    public function setDisableWebPagePreview(): self
    {
        $this->disableWebPagePreview = true;

        return $this;
    }


}