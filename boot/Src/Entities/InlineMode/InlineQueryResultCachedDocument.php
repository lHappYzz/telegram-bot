<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcacheddocument
 */
class InlineQueryResultCachedDocument extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $title
     * @param string $documentFileId
     * @param string|null $description
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $title,
        protected string $documentFileId,
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
        return 'document';
    }
}