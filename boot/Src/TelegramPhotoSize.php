<?php

namespace Boot\Src;

/**
 * Class telegramPhotoSize represents info about one photo
 * @link https://core.telegram.org/bots/api#photosize
 */
class TelegramPhotoSize
{
    private string $fileID;
    private string $fileUniqueID;
    private int $width;
    private int $height;
    private ?int $fileSize;

    public function __construct(array $photoData)
    {
        $this->fileID = $photoData['file_id'];
        $this->fileUniqueID = $photoData['file_unique_id'];
        $this->width = $photoData['width'];
        $this->height = $photoData['height'];
        $this->fileSize = $photoData['file_size'];
    }

    public function getFileID(): string
    {
        return $this->fileID;
    }

    public function getFileUniqueID(): string
    {
        return $this->fileUniqueID;
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