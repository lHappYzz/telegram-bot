<?php

namespace Boot\Src\Entities\InputMedia;

use Boot\Classes\FileValidator;
use Boot\Src\Entities\InputFile;
use InvalidArgumentException;

/**
 * Represents an audio file to be treated as music to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmediaaudio
 */
class InputMediaAudio extends InputMedia
{
    /**
     * @param InputFile|string $media
     * @param string|null $parseMode
     * @param array|null $captionEntities
     * @param InputFile|null $thumbnail
     * @param string|null $caption
     * @param int|null $duration
     * @param string|null $performer
     * @param string|null $title
     */
    public function __construct(
        InputFile|string $media,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        protected ?InputFile $thumbnail = null,
        protected ?string $caption = null,
        protected ?int $duration = null,
        protected ?string $performer = null,
        protected ?string $title = null
    ) {
        parent::__construct($media, $parseMode, $captionEntities);

    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'audio';
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->media instanceof InputFile) {
            container(FileValidator::class, ['path' => $this->media->filePath])->validateAudio();
        }

        if ($this->thumbnail) {
            container(FileValidator::class, ['path' => $this->thumbnail->filePath])->validateThumbnail();
        }

        if (mb_strlen($this->caption) > 1024) {
            throw new InvalidArgumentException('Caption must be at most 1024 characters.');
        }
    }
}