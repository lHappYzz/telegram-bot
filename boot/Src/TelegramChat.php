<?php

namespace Boot\Src;

use Boot\Interfaces\Recordable;
use Boot\Traits\Helpers;

/**
 * Class telegramChat
 * @link https://core.telegram.org/bots/api#chat
 */
class TelegramChat extends Entity implements Recordable
{
    use Helpers;

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

    public function getId(): int
    {
        if (empty($this->id)) {
            return 423303268;
        }
        return $this->id;
    }

    public function getType(): string
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

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getArrayOfAttributes(array $fillableColumns): array
    {
        $arrayOfAttributes = [];

        foreach ($fillableColumns as $fillableColumn) {
            $propertyValue = $this->{$this->snakeCaseToCamelCase($fillableColumn)};
            $arrayOfAttributes[$fillableColumn] = $propertyValue;
        }

        return $arrayOfAttributes;
    }
}