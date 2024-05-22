<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;

/**
 * This object represents a voice note.
 *
 * @link https://core.telegram.org/bots/api#voice
 */
class Voice extends Entity
{
    /**
     * @param string $fileId
     * @param string $fileUniqueId
     * @param int $duration
     * @param string|null $mimeType
     * @param int|null $fileSize
     */
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected int $duration,
        protected ?string $mimeType = null,
        protected ?int $fileSize = null,
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