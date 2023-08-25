<?php

namespace Boot\Src;

use Boot\Src\Abstracts\Entity;

/**
 * Class telegramPhotoSize represents info about one photo
 * @link https://core.telegram.org/bots/api#photosize
 */
class PhotoSize extends Entity
{
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected int $width,
        protected int $height,
        protected ?int $fileSize,
    ) {}

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function getFileUniqueId(): string
    {
        return $this->fileUniqueId;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }
}