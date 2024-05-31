<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;

/**
 * This object represents an audio file to be treated as music by the Telegram clients.
 *
 * @link https://core.telegram.org/bots/api#audio
 */
class Audio extends Entity
{
    /**
     * @param string $fileId
     * @param string $fileUniqueId
     * @param int $duration
     * @param string|null $performer
     * @param string|null $title
     * @param string|null $fileName
     * @param string|null $mimeType
     * @param int|null $fileSize
     * @param PhotoSize|null $thumbnail
     */
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected int $duration,
        protected ?string $performer = null,
        protected ?string $title = null,
        protected ?string $fileName = null,
        protected ?string $mimeType = null,
        protected ?int $fileSize = null,
        protected ?PhotoSize $thumbnail = null,
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
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return string|null
     */
    public function getPerformer(): ?string
    {
        return $this->performer;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
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

    /**
     * @return PhotoSize|null
     */
    public function getThumbnail(): ?PhotoSize
    {
        return $this->thumbnail;
    }
}