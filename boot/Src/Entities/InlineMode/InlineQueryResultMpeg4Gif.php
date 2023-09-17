<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultmpeg4gif
 */
class InlineQueryResultMpeg4Gif extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $mpeg4Url
     * @param string $thumbnailUrl
     * URL of the static (JPEG or GIF) or animated (MPEG4) thumbnail for the result
     * @param int|null $mpeg4Width
     * @param int|null $mpeg4Height
     * @param int|null $mpeg4Duration
     * @param string|null $thumbnailMimeType
     * MIME type of the thumbnail, must be one of “image/jpeg”, “image/gif”, or “video/mp4”. Defaults to “image/jpeg”
     * @param string|null $title
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $mpeg4Url,
        protected string $thumbnailUrl,
        protected ?int $mpeg4Width = null,
        protected ?int $mpeg4Height = null,
        protected ?int $mpeg4Duration = null,
        protected ?string $thumbnailMimeType = null,
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