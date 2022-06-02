<?php

namespace Boot\Src;

use Boot\Traits\Helpers;
use JetBrains\PhpStorm\Pure;

class TelegramPhoto extends TelegramFile
{
    use Helpers;

    public function __construct(array $telegramUploadedPhotosList, string $caption)
    {
        $photoData = $this->arrayLast($telegramUploadedPhotosList);
        $this->telegramPhotoSize = new TelegramPhotoSize($photoData);
        $this->type = TelegramFile::MESSAGE_FILE_PHOTO;
        $this->caption = $caption;
    }

    #[Pure] public function getFileID(): string
    {
        return $this->telegramPhotoSize->getFileID();
    }
}