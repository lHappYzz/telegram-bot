<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\FileValidator;
use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;
use Boot\Src\Exceptions\TelegramMethod\UploadingInvalidFileException;
use InvalidArgumentException;

class SendAnimation extends TelegramMethod
{
    /**
     * @param string $chatId
     * @param InputFile|string $animation
     * @param InputFile|null $thumbnail
     * @param int|null $duration
     * @param int|null $width
     * @param int|null $height
     * @param string|null $caption
     * @param string|null $parseMode
     * @param array|null $captionEntities
     * @param bool $showCaptionAboveMedia
     * @param bool $hasSpoiler
     * @param ReplyMarkup|null $replyMarkup
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        protected string $chatId,
        protected InputFile|string $animation,
        protected InputFile|null $thumbnail = null,
        protected ?int $duration = null,
        protected ?int $width = null,
        protected ?int $height = null,
        protected ?string $caption = null,
        protected ?string $parseMode = null,
        protected ?array $captionEntities = null,
        protected bool $showCaptionAboveMedia = false,
        protected bool $hasSpoiler = false,
        protected ?ReplyMarkup $replyMarkup = null,
        protected ?MethodOptionalFields $optionalFields = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'sendAnimation';
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        try {
            if ($this->animation instanceof InputFile) {
                container(FileValidator::class, ['path' => $this->animation->filePath])->validateAnimation();
            }

            if ($this->thumbnail !== null) {
                container(FileValidator::class, ['path' => $this->thumbnail->filePath])->validateThumbnail();
            }
        } catch (InvalidArgumentException $e) {
            throw new UploadingInvalidFileException($e);
        }

        if ($this->caption !== null && mb_strlen($this->caption) > 1024) {
            throw new StringLengthExceededException('caption', 1024, $this->getMethodName());
        }
    }
}