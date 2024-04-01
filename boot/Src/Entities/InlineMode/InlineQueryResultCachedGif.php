<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedgif
 */
class InlineQueryResultCachedGif extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $gifFileId
     * @param string|null $title
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $gifFileId,
        protected ?string $title = null,
        protected ?string $caption = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'gif';
    }
}