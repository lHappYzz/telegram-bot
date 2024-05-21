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

    /**
     * @param string $path
     * @return void
     * @throws InvalidArgumentException
     */
    public function validatePhoto(string $path): void
    {
        $this->baseRules($path);
        $this->ensureFileSize($path, self::MAX_PHOTO_SIZE_MB * 1024 * 1024);

        $imageSize = getimagesize($path);
        if ($imageSize === false) {
            throw new InvalidArgumentException("Unable to get image size: $path");
        }

        $width = $imageSize[0];
        $height = $imageSize[1];

        if (($width + $height) > self::MAX_PHOTO_HEIGHT_WIDTH_SUM) {
            throw new InvalidArgumentException("The photo's width and height must not exceed " . self::MAX_PHOTO_HEIGHT_WIDTH_SUM . " in total.");
        }

        $ratio = max($width / $height, $height / $width);
        if ($ratio > self::MAX_PHOTO_RATIO) {
            throw new InvalidArgumentException("Width and height ratio must be at most " . self::MAX_PHOTO_RATIO);
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if (!$finfo) {
            throw new RuntimeException("Unable to initialize Fileinfo resource.");
        }

        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);

        if (!in_array($mimeType, [
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true)) {
            throw new InvalidArgumentException("File has wrong MIME type: $mimeType");
        }
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
}