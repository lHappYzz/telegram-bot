<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;

/**
 * @link https://core.telegram.org/bots/api#videonote
 *
 * This object represents a video message (available in Telegram apps as of v.4.0).
 */
class VideoNote extends Entity
{
    /**
     * @param string $fileId
     * @param string $fileUniqueId
     * @param int $length
     * @param int $duration
     * @param PhotoSize|null $thumbnail
     * @param int|null $fileSize
     * Size in bytes
     */
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected int $length,
        protected int $duration,
        protected ?PhotoSize $thumbnail,
        protected ?int $fileSize,
    ) {}

    /**
     * @return string
     */
    public function getFileId(): string
    {
        return $this->fileId;
    }

    /**
     * @return string
     */
    public function getFileUniqueId(): string
    {
        return $this->fileUniqueId;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return PhotoSize|null
     */
    public function getThumbnail(): ?PhotoSize
    {
        return $this->thumbnail;
    }

    /**
     * @return int|null
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }
}