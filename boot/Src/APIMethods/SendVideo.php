<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\FileValidator;
use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;
use Boot\Src\Exceptions\TelegramMethod\UploadingInvalidFileException;
use InvalidArgumentException;

class SendVideo extends TelegramMethod
{
    /**
     * @param string $chatId
     * @param InputFile|string $video
     * @param InputFile|null $thumbnail
     * @param ReplyMarkup|null $replyMarkup
     * @param int|null $duration
     * @param int|null $width
     * @param int|null $height
     * @param string|null $caption
     * @param array|null $captionEntities
     * @param string|null $parseMode
     * @param bool $hasSpoiler
     * @param bool $supportsStreaming
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        public string $chatId,
        public InputFile|string $video,
        public InputFile|null $thumbnail = null,
        public ?ReplyMarkup $replyMarkup = null,
        public ?int $duration = null,
        public ?int $width = null,
        public ?int $height = null,
        public ?string $caption = null,
        public ?array $captionEntities = null,
        public ?string $parseMode = null,
        public bool $hasSpoiler = false,
        public bool $supportsStreaming = false,
        public ?MethodOptionalFields $optionalFields = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        try {
            if ($this->video instanceof InputFile) {
                container(FileValidator::class, ['path' => $this->video->filePath])->validateVideo();
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

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'sendVideo';
    }
}