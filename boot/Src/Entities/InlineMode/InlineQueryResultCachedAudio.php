<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedaudio
 */
class InlineQueryResultCachedAudio extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $audioFileId
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $audioFileId,
        protected ?string $caption = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'audio';
    }
}