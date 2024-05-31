<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;

/**
 * This object represents one size of a photo or a file / sticker thumbnail.
 * @link https://core.telegram.org/bots/api#photosize
 */
class PhotoSize extends Entity
{
    /**
     * @param string $fileId
     * @param string $fileUniqueId
     * @param int $width
     * @param int $height
     * @param int|null $fileSize
     */
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected int $width,
        protected int $height,
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
     * @return int|null
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }
}