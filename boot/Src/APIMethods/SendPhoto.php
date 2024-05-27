<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\FileValidator;
use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Exceptions\TelegramMethod\StringLengthExceededException;
use Boot\Src\Exceptions\TelegramMethod\UploadingInvalidFileException;
use InvalidArgumentException;

class SendPhoto extends TelegramMethod
{
    /**
     * @param string $chatId
     * @param InputFile|string $photo
     * @param string|null $caption
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $hasSpoiler
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        public string $chatId,
        public InputFile|string $photo,
        public ?string $caption = null,
        public ?ReplyMarkup $replyMarkup = null,
        public ?string $parseMode = null,
        public bool $hasSpoiler = false,
        public ?MethodOptionalFields $optionalFields = null
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        /** @var FileValidator $fileValidator */
        $fileValidator = container(FileValidator::class, ['path' => $this->photo->filePath]);

        try {
            $fileValidator->validatePhoto();
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
        return 'sendPhoto';
    }
}