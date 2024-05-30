<?php

namespace Boot\Src\Entities;

use Boot\Facades\TelegramFacade;
use Boot\Src\Abstracts\Entity;

/**
 * This object represents a file ready to be downloaded. The file can be downloaded via the link
 * https://api.telegram.org/file/bot<token>/<file_path>. It is guaranteed that the link will be valid for at least
 * 1 hour. When the link expires, a new one can be requested by calling getFile.
 *
 * @link https://core.telegram.org/bots/api#file
 * @see TelegramFacade::getFile()
 */
class File extends Entity
{
    /**
     * @param string $fileId
     * @param string $fileUniqueId
     * @param int|null $fileSize
     * @param string|null $filePath
     */
    public function __construct(
        protected string $fileId,
        protected string $fileUniqueId,
        protected ?int $fileSize = null,
        protected ?string $filePath = null,
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
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }
}