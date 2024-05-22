<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;
use Boot\Src\Entities\PhotoSize;
use Boot\Traits\Helpers;

/**
 * This class represents a video file
 * @link https://core.telegram.org/bots/api#video
 */
class Video extends Entity
{
    use Helpers;

    /**
     * @param string $fileId
     * @param string $fileUniqueId
     * @param int $width
     * @param int $height
     * @param int $duration
     * @param PhotoSize|null $thumbnail
     * @param string|null $fileName
     * @param string|null $mimeType
     * @param int|null $fileSize
     */
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected int $width,
        protected int $height,
        protected int $duration,
        protected ?PhotoSize $thumbnail,
        protected ?string $fileName,
        protected ?string $mimeType,
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
    public function getWidth(): int
    {
        return $this->width;
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
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @return int|null
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }
}