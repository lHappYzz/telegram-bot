<?php

namespace Boot\Src\Entities;

use App\Records\ChatRecord;
use App\Records\StatusRecord;
use Boot\Cache\Cache;
use Boot\Interfaces\ChatState;
use Boot\Interfaces\Recordable;
use Boot\Log\Logger;
use Boot\Src\Abstracts\Entity;
use ReflectionClass;
use ReflectionException;

/**
 * Class telegramChat
 * @link https://core.telegram.org/bots/api#chat
 */
class TelegramChat extends Entity implements Recordable
{
    /** @var string */
    private string $chatStateCacheKey;

    /**
     * Current chat state
     * @var ChatState
     * @see StatusRecord
     */
    protected ChatState $state;

    public function __construct(
        protected int $id,
        protected string $type,
        protected ?string $firstName,
        protected ?string $lastName,
        protected ?string $username,
    ) {
        $this->chatStateCacheKey = 'chat_status' . $this->getId();
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

    public function getUsername(): string
    {
        return $this->username;
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

    public function setStatus(StatusRecord $record): bool
    {
        $isSuccess = ChatRecord::query()
            ->where('id', $this->getId())
            ->update(['status_id' => $record->getId()]);

        if ($isSuccess) {
            Cache::getInstance()->set($this->chatStateCacheKey, $record->getId(), 60*60);
            $this->transitionTo($record->getId());
        }

        return $isSuccess;
    }

    public function getStatusId(): ?int
    {
        return Cache::getInstance()->get($this->chatStateCacheKey) ??
            array_first(
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
        } catch (ReflectionException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            die;
        }
    }
}