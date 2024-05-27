<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\FileValidator;
use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\UploadingInvalidFileException;
use InvalidArgumentException;

class SendVideoNote extends TelegramMethod
{
    /**
     * @param string $chatId
     * @param InputFile|string $videoNote
     * @param InputFile|null $thumbnail
     * @param ReplyMarkup|null $replyMarkup
     * @param int|null $duration
     * @param int|null $length
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        public string $chatId,
        public InputFile|string $videoNote,
        public ?InputFile $thumbnail = null,
        public ?ReplyMarkup $replyMarkup = null,
        public ?int $duration = null,
        public ?int $length = null,
        public ?MethodOptionalFields $optionalFields = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        try {
            if ($this->videoNote instanceof InputFile) {
                container(FileValidator::class, ['path' => $this->videoNote->filePath])->validateVideoNote();
            }

            if ($this->thumbnail !== null) {
                container(FileValidator::class, ['path' => $this->thumbnail->filePath])->validateThumbnail();
            }
        } catch (InvalidArgumentException $e) {
            throw new UploadingInvalidFileException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'sendVideoNote';
    }
}