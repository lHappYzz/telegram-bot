<?php

namespace Boot\Src\APIMethods;

use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputMedia\InputMedia;
use Boot\Src\Exceptions\TelegramMethod\InvalidTelegramMethodFieldException;
use InvalidArgumentException;

class SendMediaGroup extends TelegramMethod
{
    /**
     * @param string $chatId
     * @param InputMedia[] $media
     * @param MethodOptionalFields|null $optionalFields
     */
    public function __construct(
        protected string $chatId,
        protected array $media,
        protected ?MethodOptionalFields $optionalFields = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if (count($this->media) < 2 || count($this->media) > 10) {
            throw new InvalidTelegramMethodFieldException('Media must include 2-10 items. Given ' .
                count($this->media) . '.'
            );

        }

        $givenMediaTypes = [];
        foreach ($this->media as $media) {
            try {
                $media->validate();
                $givenMediaTypes[$media->getType()] = true;
            } catch (InvalidArgumentException $e) {
                throw new InvalidTelegramMethodFieldException($e);
            }
        }

        if (isset($givenMediaTypes['document']) && count($givenMediaTypes) > 1) {
            throw new InvalidTelegramMethodFieldException("Document can't be mixed with other types.");
        }

        if (isset($givenMediaTypes['audio']) && count($givenMediaTypes) > 1) {
            throw new InvalidTelegramMethodFieldException("Audio can't be mixed with other types.");
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'sendMediaGroup';
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $serialized = parent::jsonSerialize();

        /** @var InputMedia|string $media */
        foreach ($serialized['media'] as &$media) {
            $media = $media->jsonSerialize();
            if (!is_string($media['media'])) {
                $index = 'file_' . uniqid();
                $serialized[$index] = $media['media'];
                $media['media'] = "attach://$index";
            }
        }

        $serialized['media'] = json_encode($serialized['media']);

        return $serialized;
    }
}