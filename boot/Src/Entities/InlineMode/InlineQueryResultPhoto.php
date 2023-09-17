<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultphoto
 */
class InlineQueryResultPhoto extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $photoUrl
     * A valid URL of the photo. Photo must be in JPEG format. Photo size must not exceed 5MB
     * @param string $thumbnailUrl
     * @param int|null $photoWidth
     * @param int|null $photoHeight
     * @param string|null $title
     * @param string|null $description
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $photoUrl,
        protected string $thumbnailUrl,
        protected ?int $photoWidth = null,
        protected ?int $photoHeight = null,
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