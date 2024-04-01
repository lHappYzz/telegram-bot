<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedvideo
 */
class InlineQueryResultCachedVideo extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $videoFileId
     * @param string $title
     * @param string|null $caption
     * @param string|null $description
     */
    public function __construct(
        string $id,
        protected string $videoFileId,
        protected string $title,
        protected ?string $caption = null,
        protected ?string $description = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'video';
    }
}