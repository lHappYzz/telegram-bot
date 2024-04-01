<?php

namespace Boot\Src;

use Boot\Traits\Helpers;

/**
 * This class represents a video file
 * @link https://core.telegram.org/bots/api#video
 */
class TelegramVideo extends TelegramFile
{
    use Helpers;

    private string $fileID;
    private string $fileUniqueID;
    private int $width;
    private int $height;
    private int $duration;

    /** @var ?PhotoSize */
    private ?PhotoSize $thumb;

    private ?string $fileName;
    private ?string $mimeType;
    private ?int $fileSize;

    public function __construct(array $videoData, string $caption)
    {
        $this->fileID = $videoData['file_id'];
        $this->fileUniqueID = $videoData['file_unique_id'];
        $this->width = $videoData['width'];
        $this->height = $videoData['height'];
        $this->duration = $videoData['duration'];
        $this->fileName = $videoData['file_name'];
        $this->mimeType = $videoData['mime_type'];
        $this->fileSize = $videoData['file_size'];

        $this->telegramPhotoSize = new PhotoSize($videoData);
        $this->thumb = new PhotoSize($videoData['thumb']);
        $this->type = TelegramFile::MESSAGE_FILE_VIDEO;
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getFileID(): string
    {
        return $this->fileID;
    }

    /**
     * @param string $fileID
     */
    public function setFileID(string $fileID): void
    {
        $this->fileID = $fileID;
    }

    /**
     * @return string
     */
    public function getFileUniqueID(): string
    {
        return $this->fileUniqueID;
    }

    /**
     * @param string $fileUniqueID
     */
    public function setFileUniqueID(string $fileUniqueID): void
    {
        $this->fileUniqueID = $fileUniqueID;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return PhotoSize
     */
    public function getThumb(): PhotoSize
    {
        return $this->thumb;
    }

    /**
     * @param PhotoSize $thumb
     */
    public function setThumb(PhotoSize $thumb): void
    {
        $this->thumb = $thumb;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return int|null
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * @param int $fileSize
     */
    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }
}