<?php

namespace Boot\Src;

/**
 * Class telegramChat
 * @link https://core.telegram.org/bots/api#chat
 */
class TelegramChat extends Entity
{
    private int $id;
    private string $type;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $userName;

    public function __construct(array $telegramChatData) {
        $this->id = $telegramChatData['id'];
        $this->type = $telegramChatData['type'];
        $this->firstName = $telegramChatData['firstName'];
        $this->lastName = $telegramChatData['lastName'];
        $this->userName = $telegramChatData['userName'];
    }

    public function getChatID(): int
    {
        if (empty($this->id)) {
            return 423303268;
        }
        return $this->id;
    }

    public function getChatType(): string
    {
        return $this->type;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUsername(): string
    {
        return $this->userName;
    }
}