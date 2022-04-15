<?php

namespace Boot\Src;

/**
 * Class TelegramUser
 * @link https://core.telegram.org/bots/api#user
 */
class TelegramUser extends Entity
{
    private int $id;
    private bool $isBot;
    private string $firstName;
    private ?string $lastName;
    private ?string $userName;
    private ?string $languageCode;

    public function __construct($userData)
    {
        $this->id = $userData['id'];
        $this->firstName = $userData['first_name'];
        $this->lastName = $userData['last_name'];
        $this->userName = $userData['username'];
        $this->languageCode = $userData['language_code'];
        $this->isBot = $userData['is_bot'];
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }
}