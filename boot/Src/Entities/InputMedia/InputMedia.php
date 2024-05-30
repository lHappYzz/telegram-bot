<?php

namespace Boot\Src\Entities\InputMedia;

use Boot\Interfaces\TelegramRequirementsValidate;
use Boot\Src\Abstracts\JsonSerializableEntity;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\MessageEntity;

/**
 * This object represents the content of a media message to be sent. It should be one of
 * @see InputMediaAudio
 * @see InputMediaPhoto
 * @see InputMediaVideo
 * @see InputMediaDocument
 * @see InputMediaAnimation
 *
 * @link https://core.telegram.org/bots/api#inputmedia
 */
abstract class InputMedia extends JsonSerializableEntity implements TelegramRequirementsValidate
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @param InputFile|string $media
     * @param string|null $parseMode
     * @param MessageEntity[]|null $captionEntities
     */
    public function __construct(
        protected InputFile|string $media,
        protected ?string $parseMode = null,
        protected ?array $captionEntities = null,
    )
    {
        $this->type = $this->getType();
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $serialized = parent::jsonSerialize();

        if ($this->media instanceof InputFile) {
            $serialized['media'] = $this->media->getFile();
        }

        return $serialized;
    }
}