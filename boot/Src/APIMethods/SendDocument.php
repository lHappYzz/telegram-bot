<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\FileValidator;
use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;
use Boot\Src\Exceptions\TelegramMethod\UploadingInvalidFileException;
use InvalidArgumentException;

class SendDocument extends TelegramMethod
{
    /**
     * @param string $chatId
     * @param InputFile|string $document
     * @param InputFile|null $thumbnail
     * @param string|null $caption
     * @param string|null $parseMode
     * @param array|null $captionEntities
     * @param bool $disableContentTypeDetection
     * @param ReplyMarkup|null $replyMarkup
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        public string $chatId,
        public InputFile|string $document,
        public ?InputFile $thumbnail = null,
        public ?string $caption = null,
        public ?string $parseMode = null,
        public ?array $captionEntities = null,
        public bool $disableContentTypeDetection = false,
        public ?ReplyMarkup $replyMarkup = null,
        public ?MethodOptionalFields $optionalFields = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        try {
            if ($this->document instanceof InputFile) {
                container(FileValidator::class, ['path' => $this->document->filePath])->validateDocument();
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
        return 'sendDocument';
    }
}