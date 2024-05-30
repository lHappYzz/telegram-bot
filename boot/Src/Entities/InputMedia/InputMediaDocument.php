<?php

namespace Boot\Src\Entities\InputMedia;

use Boot\Classes\FileValidator;
use Boot\Src\Entities\InputFile;
use InvalidArgumentException;

/**
 * Represents a general file to be sent.
 *
 * @link Represents a general file to be sent.
 */
class InputMediaDocument extends InputMedia
{
    /**
     * @param InputFile|string $media
     * @param string|null $parseMode
     * @param array|null $captionEntities
     * @param InputFile|null $thumbnail
     * @param string|null $caption
     * @param bool $disableContentTypeDetection
     */
    public function __construct(
        InputFile|string $media,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        protected ?InputFile $thumbnail = null,
        protected ?string $caption = null,
        protected bool $disableContentTypeDetection = false,
    ) {
        parent::__construct($media, $parseMode, $captionEntities);
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'document';
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->media instanceof InputFile) {
            container(FileValidator::class, ['path' => $this->media->filePath])->validateDocument();
        }

        if ($this->thumbnail) {
            container(FileValidator::class, ['path' => $this->thumbnail->filePath])->validateThumbnail();
        }

        if (mb_strlen($this->caption) > 1024) {
            throw new InvalidArgumentException('Caption must be at most 1024 characters.');
        }
    }
}