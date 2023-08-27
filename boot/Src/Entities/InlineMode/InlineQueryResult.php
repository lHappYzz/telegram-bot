<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresult
 */
abstract class InlineQueryResult extends JsonSerializableEntity
{
    /** @var string */
    protected string $type;

    /**
     * @param string $id
     */
    public function __construct(
        protected string $id,
    ) {
        $this->type = $this->getType();
    }

    /**
     * @return string
     */
    abstract protected function getType(): string;
}