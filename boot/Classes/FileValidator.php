<?php

namespace Boot\Classes;

use getID3;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use RuntimeException;

/**
 * Used to validate files uploaded to Telegram servers.
 * @link https://core.telegram.org/bots/api#sending-files
 */
class FileValidator
{
    private const MAX_PHOTO_SIZE_MB = 10;
    private const MAX_PHOTO_HEIGHT_WIDTH_SUM = 10000;
    private const MAX_PHOTO_RATIO = 20;

    private const MAX_VIDEO_SIZE_MB = 50;
    private const MAX_VIDEO_THUMBNAIL_SIZE_KB = 200;

    private const MAX_AUDIO_SIZE_MB = 50;

    private const MAX_DOCUMENT_SIZE_MB = 50;

    /**
     * @var array
     * ArrayShape is not full
     */
    #[ArrayShape([
        'filesize' => 'int',
        'audio' => [
            'dataformat' => 'string',
            'bitrate' => 'int',
            'sample_rate' => 'int',
            'codec' => 'string',
            'lossless' => 'bool',
        ],
        'video' => [
            'resolution_x' => 'int',
            'resolution_y' => 'int',
        ],
        'mime_type' => 'string',
    ])] private array $fileInfo;

    /**
     * @param getID3 $getID3
     * @param string $path
     * Local path
     */
    public function __construct(
        protected getID3 $getID3,
        private string $path
    ) {
        $this->fileInfo = $this->analyze();
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function validatePhoto(): void
    {
        $this->baseRules();
        $this->ensureFileSize(self::MAX_PHOTO_SIZE_MB * 1024 * 1024);
        $this->ensureFileRatio(self::MAX_PHOTO_RATIO);

        $width = $this->fileInfo['video']['resolution_x'];
        $height = $this->fileInfo['video']['resolution_y'];

        if (($width + $height) > self::MAX_PHOTO_HEIGHT_WIDTH_SUM) {
            throw new InvalidArgumentException("The photo's width and height must not exceed " . self::MAX_PHOTO_HEIGHT_WIDTH_SUM . " in total.");
        }

        $this->ensureFileMimeType([
            'image/jpeg',
            'image/png',
            'image/webp',
        ]);
    }

    /**
     * @return void
     */
    public function validateVideo(): void
    {
        $this->baseRules();
        $this->ensureFileSize(self::MAX_VIDEO_SIZE_MB * 1024 * 1024);
        $this->ensureFileMimeType([
            'video/mp4',
            'video/mpeg4',
        ]);
    }

    /**
     * @return void
     */
    public function validateVideoNote(): void
    {
        $this->validateVideo();
        $this->ensureFileRatio(1);

        $width = $this->fileInfo['video']['resolution_x'];
        $height = $this->fileInfo['video']['resolution_y'];

        if ($width > 240 || $width != $height) {
            throw new InvalidArgumentException("File dimensions should not exceed 240x240: $this->path");
        }
    }

    /**
     * @return void
     */
    public function validateThumbnail(): void
    {
        $this->baseRules();
        $this->ensureFileSize(self::MAX_VIDEO_THUMBNAIL_SIZE_KB * 1024);
        $this->ensureFileMimeType(['image/jpeg']);

        $width = $this->fileInfo['video']['resolution_x'];
        $height = $this->fileInfo['video']['resolution_y'];

        if ($width > 320 || $height > 320) {
            throw new InvalidArgumentException("Thumbnail dimensions should not exceed 320x320: $this->path");
        }
    }

    /**
     * @return void
     */
    public function validateAudio(): void
    {
        $this->baseRules();
        $this->ensureFileSize(self::MAX_AUDIO_SIZE_MB * 1024 * 1024);
        $this->ensureFileMimeType(['audio/mpeg', 'audio/m4a', 'audio/x-m4a']);
    }

    /**
     * @return void
     */
    public function validateDocument(): void
    {
        $this->baseRules();
        $this->ensureFileSize(self::MAX_DOCUMENT_SIZE_MB * 1024 * 1024);
        $this->ensureFileMimeType([
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf',
            'application/zip',
        ]);
    }

    /**
     * @return void
     */
    public function validateAnimation(): void
    {
        $this->baseRules();
        $this->ensureFileSize(self::MAX_VIDEO_SIZE_MB * 1024 * 1024);
        $this->ensureFileMimeType([
            'image/gif',
            'video/mp4',
            'video/x-m4v',
            'video/h264',
        ]);
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    private function baseRules(): void
    {
        if (filter_var($this->path, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Path must be a local file, not an URL: $this->path");
        }

        if (!is_readable($this->path)) {
            throw new InvalidArgumentException("File is not readable: $this->path");
        }
    }

    /**
     * @param int $maxSize
     * Maximum allowed file size in bytes
     *
     * @return void
     */
    private function ensureFileSize(int $maxSize): void
    {
        $fileSize = $this->fileInfo['filesize'];

        if (!$fileSize) {
            throw new RuntimeException("Unable to get file size: $this->path");
        }

        if ($fileSize > $maxSize) {
            throw new InvalidArgumentException(
                "File is too large. Maximum allowed size is " .
                ($maxSize / 1024 / 1024) . " MB, given: " . $fileSize / 1024 / 1024 . ' MB.'
            );
        }
    }

    /**
     * @param string[] $allowedMimeTypes
     * @return void
     */
    private function ensureFileMimeType(array $allowedMimeTypes): void
    {
        if (!in_array($this->fileInfo['mime_type'], $allowedMimeTypes, true)) {
            throw new InvalidArgumentException('File has wrong MIME type: ' . $this->fileInfo['mime_type']);
        }
    }

    /**
     * @param int $maxAllowedRatio
     * @return void
     */
    private function ensureFileRatio(int $maxAllowedRatio): void
    {
        $width = $this->fileInfo['video']['resolution_x'];
        $height = $this->fileInfo['video']['resolution_y'];

        $ratio = max($width / $height, $height / $width);
        if ($ratio > $maxAllowedRatio) {
            throw new InvalidArgumentException(
                "Width and height ratio must not exceed - $maxAllowedRatio. 
                Given file with ration - $ratio: $this->path"
            );
        }
    }

    /**
     * @return array
     */
    private function analyze(): array
    {
        $fileInfo = $this->getID3->analyze($this->path);

        if (array_key_exists('error', $fileInfo)) {
            throw new RuntimeException("Unable to get file info: $this->path. " . $fileInfo['error']);
        }

        return $fileInfo;
    }
}