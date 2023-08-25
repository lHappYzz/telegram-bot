<?php

namespace Boot\Src\Entities;

use Boot\Interfaces\Recordable;
use Boot\Src\Abstracts\Entity;

/**
 * Class TelegramUser
 * @link https://core.telegram.org/bots/api#user
 */
class TelegramUser extends Entity implements Recordable
{

    public function __construct(
        protected int $id,
        protected bool $isBot,
        protected string $firstName,
        protected ?string $lastName,
        protected ?string $username,
        protected ?string $languageCode,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }

    public function getArrayOfAttributes(array $fillableColumns): array
    {
        $arrayOfAttributes = [];

        foreach ($fillableColumns as $fillableColumn) {
            $propertyValue = $this->{snake_case_to_camel_case($fillableColumn)};
            $arrayOfAttributes[$fillableColumn] = $propertyValue;
        }

        return $arrayOfAttributes;
    }
}