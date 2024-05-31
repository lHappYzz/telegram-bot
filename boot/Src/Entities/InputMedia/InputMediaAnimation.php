<?php

namespace Boot\Src\Entities\InputMedia;

use Boot\Classes\FileValidator;
use Boot\Src\Entities\InputFile;
use InvalidArgumentException;

/**
 * Represents an animation file (GIF or H.264/MPEG-4 AVC video without sound) to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmediaanimation
 * @deprecated This class is described in the documentation but is currently not supported by the Telegram API.
 */
class InputMediaAnimation extends InputMedia
{
    /**
     * @param string|InputFile $media
     * @param string|null $parseMode
     * @param array|null $captionEntities
     * @param InputFile|null $thumbnail
     * @param string|null $caption
     * @param int|null $width
     * @param int|null $height
     * @param int|null $duration
     * @param bool $showCaptionAboveMedia
     * @param bool $hasSpoiler
     */
    public function __construct(
        string|InputFile $media,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        protected ?InputFile $thumbnail = null,
        protected ?string $caption = null,
        protected ?int $width = null,
        protected ?int $height = null,
        protected ?int $duration = null,
        protected bool $showCaptionAboveMedia = false,
        protected bool $hasSpoiler = false,
    ) {
        parent::__construct($media, $parseMode, $captionEntities);
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'animation';
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->media instanceof InputFile) {
            container(FileValidator::class, ['path' => $this->media->filePath])->validateAnimation();
        }

        if ($this->thumbnail) {
            container(FileValidator::class, ['path' => $this->thumbnail->filePath])->validateThumbnail();
        }

        if (mb_strlen($this->caption) > 1024) {
            throw new InvalidArgumentException('Caption must be at most 1024 characters.');
        }
    }
}