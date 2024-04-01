<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedvoice
 */
class InlineQueryResultCachedVoice extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $voiceFileId
     * @param string $title
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $voiceFileId,
        protected string $title,
        protected ?string $caption = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'voice';
    }
}