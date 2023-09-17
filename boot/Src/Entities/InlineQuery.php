<?php

namespace Boot\Src\Entities;

use Boot\Responsibilities;
use Boot\Src\Abstracts\UpdateUnit;

/**
 * This object represents an incoming inline query. When the user sends an empty query,
 * your bot could return some default or trending results.
 *
 * @link https://core.telegram.org/bots/api#inline-mode
 */
class InlineQuery extends UpdateUnit
{
    public function __construct(
        protected string $id,
        protected TelegramUser $from,
        protected string $query,
        protected string $offset,
        protected ?string $chatType = null,
        protected ?Location $location = null,
    ) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return TelegramUser
     */
    public function getFrom(): TelegramUser
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getOffset(): string
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getChatType(): string
    {
        return $this->chatType;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @inheritDoc
     */
    public function responsibilize(Responsibilities $responsibility): void
    {
        $responsibility->handleInlineQuery($this);
    }
}