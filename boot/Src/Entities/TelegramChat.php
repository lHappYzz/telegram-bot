<?php

namespace Boot\Src\Entities;

use App\Records\ChatRecord;
use App\Records\StatusRecord;
use Boot\Cache\Cache;
use Boot\Interfaces\ChatState;
use Boot\Interfaces\Recordable;
use Boot\Src\Abstracts\Entity;

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

    /**
     * @param int $id
     * @param string $type
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $username
     */
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * {@inheritDoc}
     */
    public function getArrayOfAttributes(array $fillableColumns): array
    {
        $arrayOfAttributes = [];

        foreach ($fillableColumns as $fillableColumn) {
            $propertyValue = $this->{snake_case_to_camel_case($fillableColumn)};
            $arrayOfAttributes[$fillableColumn] = $propertyValue;
        }

        return $arrayOfAttributes;
    }

    /**
     * @param int $stateId
     * @return bool
     */
    public function setStatus(int $stateId): bool
    {
        $isSuccess = ChatRecord::query()
            ->where('id', $this->getId())
            ->update(['status_id' => $stateId]);

        if ($isSuccess) {
            Cache::getInstance()->set($this->chatStateCacheKey, $stateId, 60*60);
            $this->transitionTo($stateId);
        }

        return $isSuccess;
    }

    /**
     * @return ChatState
     */
    public function getChatState(): ChatState
    {
        return $this->state;
    }

    /**
     * Transition to chat state by given status record identifier through user defined state bindings.
     *
     * @see StatusRecord::statesBindings
     * @param ?int $statusRecordId
     */
    private function transitionTo(?int $statusRecordId): void
    {
        $stateClassName = StatusRecord::$statesBindings[$statusRecordId];

        $this->state = container($stateClassName);
    }

    /**
     * @return int|null
     */
    private function getStatusId(): ?int
    {
        return Cache::getInstance()->get($this->chatStateCacheKey) ??
            array_first(
                ChatRecord::query()
                    ->select(['status_id'])
                    ->where('id', $this->getId())
                    ->get()
            )->status_id;
    }
}