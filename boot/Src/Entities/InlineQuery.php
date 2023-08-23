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
        public string $id,
        public TelegramUser $from,
        public string $query,
        public string $offset,
        public string $chatType,
        public ?Location $location,
    ) {}

    /**
     * @inheritDoc
     */
    public function responsibilize(Responsibilities $responsibility): void
    {
        // TODO: Implement responsibilize() method.
    }
}