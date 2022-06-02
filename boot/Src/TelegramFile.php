<?php

namespace Boot\Src;

abstract class TelegramFile
{
    public const MESSAGE_FILE_PHOTO = 'photo';
    public const MESSAGE_FILE_VIDEO = 'video';

    /** @var ?TelegramPhotoSize represents one size of a photo or a file / sticker thumbnail. */
    protected ?TelegramPhotoSize $telegramPhotoSize = null;
    protected ?string $caption;
    protected ?string $type;

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    abstract public function getFileID(): string;
}