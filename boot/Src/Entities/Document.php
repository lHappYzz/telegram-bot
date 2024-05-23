<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;

/**
 * This object represents a general file (as opposed to photos, voice messages and audio files).
 *
 * @link https://core.telegram.org/bots/api#document
 */
class Document extends Entity
{
    /**
     * @param string $fileId
     * @param string $fileUniqueId
     * @param string|null $thumbnail
     * @param string|null $fileName
     * @param string|null $mimeType
     * @param string|null $fileSize
     */
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected ?string $thumbnail = null,
        protected ?string $fileName = null,
        protected ?string $mimeType = null,
        protected ?string $fileSize = null,
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
     * @return string|null
     */
    public function getThumbnail(): ?string
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
     * @return string|null
     */
    public function getFileSize(): ?string
    {
        return $this->fileSize;
    }
}