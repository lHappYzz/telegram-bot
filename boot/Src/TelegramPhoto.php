<?php

namespace Boot\Src;

use JetBrains\PhpStorm\Pure;

class TelegramPhoto extends TelegramFile
{
    public function __construct(array $photoData, string $caption)
    {
        $this->telegramPhotoSize = new TelegramPhotoSize($photoData);
        $this->type = TelegramFile::MESSAGE_FILE_PHOTO;
        $this->caption = $caption;
    }

    #[Pure] public function getFileID(): string
    {
        return $this->telegramPhotoSize->getFileID();
    }
}