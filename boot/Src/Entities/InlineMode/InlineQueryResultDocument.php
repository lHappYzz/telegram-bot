<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultdocument
 */
class InlineQueryResultDocument extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $title
     * @param string $documentUrl
     * @param string $mimeType
     * MIME type of the content of the file, either “application/pdf” or “application/zip”
     * @param string|null $caption
     * @param string|null $description
     * @param string|null $thumbnailUrl
     * URL of the thumbnail (JPEG only) for the file
     * @param int|null $thumbnailWidth
     * @param int|null $thumbnailHeight
     */
    public function __construct(
        string $id,
        protected string $title,
        protected string $documentUrl,
        protected string $mimeType,
        protected ?string $caption = null,
        protected ?string $description = null,
        protected ?string $thumbnailUrl = null,
        protected ?int $thumbnailWidth = null,
        protected ?int $thumbnailHeight = null,
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