<?php

namespace Boot\Src\Entities;

use App\Records\ChatRecord;
use App\Records\StatusRecord;
use Boot\Interfaces\ChatState;
use Boot\Interfaces\Recordable;
use Boot\Log\Logger;
use Boot\Src\Abstracts\Entity;
use Boot\Traits\Helpers;
use ReflectionClass;

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

    /**
     * Current chat state
     * @var ChatState
     * @see StatusRecord
     */
    protected ChatState $state;

    public function __construct(array $telegramChatData)
    {
        $this->id = $telegramChatData['id'];
        $this->type = $telegramChatData['type'];
        $this->firstName = $telegramChatData['firstName'];
        $this->lastName = $telegramChatData['lastName'];
        $this->userName = $telegramChatData['userName'];

        $this->transitionTo($this->getStatusId());
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

    public function setStatus(StatusRecord $record): bool
    {
        $isSuccess = ChatRecord::query()
            ->where('id', $this->getId())
            ->update(['status_id' => $record->getId()]);

        if ($isSuccess) {
            $this->transitionTo($record->getId());
        }

        return $isSuccess;
    }

    public function getStatusId(): ?int
    {
        return $this->arrayFirst(
            ChatRecord::query()
                ->select(['status_id'])
                ->where('id', $this->getId())
                ->get()
            )->status_id;
    }

    public function getChatState(): ChatState
    {
        return $this->state;
    }

    /**
     * Transition to chat state by given status record identifier
     * through user defined state bindings
     * @see StatusRecord::statesBindings
     * @param ?int $statusRecordId
     */
    private function transitionTo(?int $statusRecordId): void
    {
        $stateClassName = StatusRecord::$statesBindings[$statusRecordId];

        try {
            $reflection = new ReflectionClass($stateClassName);

            $fullPathToStateClass = $reflection->getName();

            $this->state = new $fullPathToStateClass($this);
        } catch (\ReflectionException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            die;
        }
    }
}