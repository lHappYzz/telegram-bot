<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Src\Entities\MessageEntity;
use Boot\Traits\WithInputMessageContent;

abstract class InlineQueryFileResult extends InlineQueryResult
{
    use WithInputMessageContent;

    public function __construct(
        string $id,
        protected ?string $parseMode = null,
        protected ?array $captionEntities = null,
    ) {
        parent::__construct($id);
    }

    /**
     * Special entity that appear in the caption, which can be specified instead of parse_mode
     *
     * @param MessageEntity $messageEntity
     * @return static
     */
    public function addCaptionEntity(MessageEntity $messageEntity): self
    {
        $this->captionEntities[] = $messageEntity;

        return $this;
    }

    /**
     * @param string $parseMode
     * @return static
     */
    public function setParseMode(string $parseMode): self
    {
        $this->parseMode = $parseMode;

        return $this;
    }
}