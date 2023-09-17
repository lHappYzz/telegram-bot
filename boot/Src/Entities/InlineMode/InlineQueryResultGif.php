<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultgif
 */
class InlineQueryResultGif extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $gifUrl
     * @param int|null $gifWidth
     * @param int|null $gifHeight
     * @param int|null $gifDuration
     * @param string|null $thumbnailUrl
     * @param string|null $thumbnailMimeType
     * @param string|null $title
     * @param string|null $caption
     */
    public function __construct(
        string $id,
        protected string $gifUrl,
        protected ?int $gifWidth = null,
        protected ?int $gifHeight = null,
        protected ?int $gifDuration = null,
        protected ?string $thumbnailUrl = null,
        protected ?string $thumbnailMimeType = null,
        protected ?string $title = null,
        protected ?string $caption = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @param int $gifWidth
     * @return InlineQueryResultGif
     */
    public function setGifWidth(int $gifWidth): self
    {
        $this->gifWidth = $gifWidth;

        return $this;
    }

    /**
     * @param int $gifHeight
     * @return InlineQueryResultGif
     */
    public function setGifHeight(int $gifHeight): self
    {
        $this->gifHeight = $gifHeight;

        return $this;
    }

    /**
     * @param int $gifDuration
     * @return InlineQueryResultGif
     */
    public function setGifDuration(int $gifDuration): self
    {
        $this->gifDuration = $gifDuration;

        return $this;
    }

    /**
     * @param string $thumbnailUrl
     * @return InlineQueryResultGif
     */
    public function setThumbnailUrl(string $thumbnailUrl): self
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    /**
     * @param string $thumbnailMimeType
     * @return InlineQueryResultGif
     */
    public function setThumbnailMimeType(string $thumbnailMimeType): self
    {
        $this->thumbnailMimeType = $thumbnailMimeType;

        return $this;
    }

    /**
     * @param string $title
     * @return InlineQueryResultGif
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $caption
     * @return InlineQueryResultGif
     */
    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'gif';
    }
}