<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultvideo
 */
class InlineQueryResultVideo extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $videoUrl
     * @param string $mimeType
     * MIME type of the content of the video URL, “text/html” or “video/mp4”
     * @param string $thumbnailUrl
     * URL of the thumbnail (JPEG only) for the video
     * @param string $title
     * @param string|null $caption
     * @param string|null $description
     * @param int|null $videoWidth
     * @param int|null $videoHeight
     * @param int|null $videoDuration
     * Video duration in seconds
     */
    public function __construct(
        string $id,
        protected string $videoUrl,
        protected string $mimeType,
        protected string $thumbnailUrl,
        protected string $title,
        protected ?string $caption = null,
        protected ?string $description = null,
        protected ?int $videoWidth = null,
        protected ?int $videoHeight = null,
        protected ?int $videoDuration = null,
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