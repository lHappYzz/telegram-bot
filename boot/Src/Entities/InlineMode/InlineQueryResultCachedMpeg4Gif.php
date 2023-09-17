<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedmpeg4gif
 */
class InlineQueryResultCachedMpeg4Gif extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $mpeg4FileId
     * @param string|null $title
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $mpeg4FileId,
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
        return 'mpeg4_gif';
    }
}