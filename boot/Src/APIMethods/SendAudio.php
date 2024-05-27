<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\FileValidator;
use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;
use Boot\Src\Exceptions\TelegramMethod\UploadingInvalidFileException;
use InvalidArgumentException;

class SendAudio extends TelegramMethod
{
    /**
     * @param string $chatId
     * @param InputFile|string $audio
     * @param InputFile|null $thumbnail
     * @param string|null $caption
     * @param array|null $captionEntities
     * @param string|null $parseMode
     * @param int|null $duration
     * @param string|null $performer
     * @param string|null $title
     * @param ReplyMarkup|null $replyMarkup
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        public string $chatId,
        public InputFile|string $audio,
        public InputFile|null $thumbnail = null,
        public ?string $caption = null,
        public ?array $captionEntities = null,
        public ?string $parseMode = null,
        public ?int $duration = null,
        public ?string $performer = null,
        public ?string $title = null,
        public ?ReplyMarkup $replyMarkup = null,
        public ?MethodOptionalFields $optionalFields = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        try {
            if ($this->audio instanceof InputFile) {
                container(FileValidator::class, ['path' => $this->audio->filePath])->validateAudio();
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
        return 'sendAudio';
    }
}