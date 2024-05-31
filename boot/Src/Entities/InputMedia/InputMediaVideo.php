<?php

namespace Boot\Src\Entities\InputMedia;

use Boot\Classes\FileValidator;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\MessageEntity;
use InvalidArgumentException;

/**
 * Represents a video to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmediavideo
 */
class InputMediaVideo extends InputMedia
{
    /**
     * @param InputFile|string $media
     * @param string|null $parseMode
     * @param MessageEntity[]|null $captionEntities
     * @param InputFile|null $thumbnail
     * @param string|null $caption
     * @param int|null $width
     * @param int|null $height
     * @param int|null $duration
     * @param bool $supportsStreaming
     * @param bool $hasSpoiler
     */
    public function __construct(
        InputFile|string $media,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        protected ?InputFile $thumbnail = null,
        protected ?string $caption = null,
        protected ?int $width = null,
        protected ?int $height = null,
        protected ?int $duration = null,
        protected bool $supportsStreaming = false,
        protected bool $hasSpoiler = false,
    ) {
        parent::__construct($media, $parseMode, $captionEntities);
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'video';
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->media instanceof InputFile) {
            container(FileValidator::class, ['path' => $this->media->filePath])->validateVideo();
        }

        if ($this->thumbnail) {
            container(FileValidator::class, ['path' => $this->thumbnail->filePath])->validateThumbnail();
        }

        if (mb_strlen($this->caption) > 1024) {
            throw new InvalidArgumentException('Caption must be at most 1024 characters.');
        }
    }
}