<?php

namespace Boot\Src\Entities\InputMedia;

use Boot\Classes\FileValidator;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\MessageEntity;
use InvalidArgumentException;

/**
 * Represents a photo to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmediaphoto
 */
class InputMediaPhoto extends InputMedia
{
    /**
     * @param InputFile|string $media
     * @param string|null $parseMode
     * @param MessageEntity[]|null $captionEntities
     * @param string|null $caption
     * @param bool $hasSpoiler
     * @param bool $showCaptionAboveMedia
     */
    public function __construct(
        InputFile|string $media,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        protected ?string $caption = null,
        protected bool $hasSpoiler = false,
        protected bool $showCaptionAboveMedia = false,
    ) {
        parent::__construct($media, $parseMode, $captionEntities);
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'photo';
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->media instanceof InputFile) {
            container(FileValidator::class, ['path' => $this->media->filePath])->validatePhoto();
        }

        if (mb_strlen($this->caption) > 1024) {
            throw new InvalidArgumentException('Caption must be at most 1024 characters.');
        }
    }
}