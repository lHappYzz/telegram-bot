<?php

namespace Boot\Classes;

use InvalidArgumentException;
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

    /**
     * @param string $path
     * @return void
     * @throws InvalidArgumentException
     */
    public function validatePhoto(string $path): void
    {
        $this->baseRules($path);
        $this->ensureFileSize($path, self::MAX_PHOTO_SIZE_MB * 1024 * 1024);

        [$width, $height] = getimagesize($path);
        if (!$width || !$height) {
            throw new InvalidArgumentException("Unable to get image size: $path");
        }

        if (($width + $height) > self::MAX_PHOTO_HEIGHT_WIDTH_SUM) {
            throw new InvalidArgumentException("The photo's width and height must not exceed " . self::MAX_PHOTO_HEIGHT_WIDTH_SUM . " in total.");
        }

        $ratio = max($width / $height, $height / $width);
        if ($ratio > self::MAX_PHOTO_RATIO) {
            throw new InvalidArgumentException("Width and height ratio must be at most " . self::MAX_PHOTO_RATIO);
        }

        $this->ensureFileMimeType($path, [
            'image/jpeg',
            'image/png',
            'image/webp',
        ]);
    }

    /**
     * @param string $path
     * @return void
     */
    public function validateVideo(string $path): void
    {
        $this->baseRules($path);
        $this->ensureFileSize($path, self::MAX_VIDEO_SIZE_MB * 1024 * 1024);
        $this->ensureFileMimeType($path, [
            'video/mp4',
            'video/mpeg4',
        ]);
    }

    /**
     * @param string $path
     * @return void
     */
    public function validateThumbnail(string $path): void
    {
        $this->baseRules($path);
        $this->ensureFileSize($path, self::MAX_VIDEO_THUMBNAIL_SIZE_KB * 1024);
        $this->ensureFileMimeType($path, ['image/jpeg']);

        [$width, $height] = getimagesize($path);
        if (!$width || !$height) {
            throw new InvalidArgumentException("Unable to get image size: $path");
        }

        if ($width > 320 || $height > 320) {
            throw new InvalidArgumentException("Thumbnail dimensions should not exceed 320x320: $path");
        }
    }

    /**
     * @param string $path
     * @return void
     */
    public function validateAudio(string $path): void
    {
        $this->baseRules($path);
        $this->ensureFileSize($path, self::MAX_AUDIO_SIZE_MB * 1024 * 1024);
        $this->ensureFileMimeType($path, ['audio/mpeg', 'audio/m4a', 'audio/x-m4a']);
    }

    /**
     * @param string $path
     * @return void
     * @throws InvalidArgumentException
     */
    private function baseRules(string $path): void
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Path must be a local file, not an URL: $path");
        }

        if (!is_readable($path)) {
            throw new InvalidArgumentException("File is not readable: $path");
        }
    }

    /**
     * @param string $path
     * @param int $maxSize
     * Maximum allowed file size in bytes
     *
     * @return void
     */
    private function ensureFileSize(string $path, int $maxSize): void
    {
        $fileSize = filesize($path);

        if ($fileSize === false) {
            throw new RuntimeException("Unable to get file size: $path");
        }

        if ($fileSize > $maxSize) {
            throw new InvalidArgumentException("File is too large. Maximum allowed size is " . ($maxSize / 1024 / 1024) . " MB.");
        }
    }

    /**
     * @param string $path
     * @param string[] $allowedMimeTypes
     * @return void
     */
    private function ensureFileMimeType(string $path, array $allowedMimeTypes): void
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if (!$finfo) {
            throw new RuntimeException("Unable to initialize Fileinfo resource.");
        }

        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new InvalidArgumentException("File has wrong MIME type: $mimeType");
        }
    }
}