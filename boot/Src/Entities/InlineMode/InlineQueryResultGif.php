<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Src\Entities\InlineMode\InputMessageContent\InputMessageContent;
use Boot\Src\Entities\MessageEntity;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardMarkup;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultgif
 */
class InlineQueryResultGif extends InlineQueryResult
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
     * @param string|null $parseMode
     * @param MessageEntity[]|null $captionEntities
     * @param InlineKeyboardMarkup|null $replyMarkup
     * @param InputMessageContent|null $inputMessageContent
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
        protected ?string $parseMode = null,
        protected ?array $captionEntities = null,
        protected ?InlineKeyboardMarkup $replyMarkup = null,
        protected ?InputMessageContent $inputMessageContent = null,
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
     * @param string $parseMode
     * @return InlineQueryResultGif
     */
    public function setParseMode(string $parseMode): self
    {
        $this->parseMode = $parseMode;

        return $this;
    }

    /**
     * @param MessageEntity $messageEntity
     * @return InlineQueryResultGif
     */
    public function addCaptionEntity(MessageEntity $messageEntity): self
    {
        $this->captionEntities[] = $messageEntity;

        return $this;
    }

    /**
     * @param InlineKeyboardMarkup $replyMarkup
     * @return InlineQueryResultGif
     */
    public function setReplyMarkup(InlineKeyboardMarkup $replyMarkup): self
    {
        $this->replyMarkup = $replyMarkup;

        return $this;
    }

    /**
     * @param InputMessageContent $inputMessageContent
     * @return InlineQueryResultGif
     */
    public function setInputMessageContent(InputMessageContent $inputMessageContent): self
    {
        $this->inputMessageContent = $inputMessageContent;

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