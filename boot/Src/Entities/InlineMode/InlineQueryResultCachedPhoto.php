<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedphoto
 */
class InlineQueryResultCachedPhoto extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $photoFileId
     * @param string|null $title
     * @param string|null $description
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $photoFileId,
        protected ?string $title = null,
        protected ?string $description = null,
        protected ?string $caption = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'photo';
    }
}