<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Src\Abstracts\JsonSerializableEntity;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardMarkup;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresult
 */
abstract class InlineQueryResult extends JsonSerializableEntity
{
    /** @var string */
    protected string $type;

    /**
     * @param string $id
     * @param InlineKeyboardMarkup|null $replyMarkup
     */
    public function __construct(
        protected string $id,
        protected ?InlineKeyboardMarkup $replyMarkup = null,
    ) {
        $this->type = $this->getType();
    }

    /**
     * @return string
     */
    abstract protected function getType(): string;

    /**
     * @param InlineKeyboardMarkup $replyMarkup
     * @return void
     */
    public function setReplyMarkup(InlineKeyboardMarkup $replyMarkup): void
    {
        $this->replyMarkup = $replyMarkup;
    }
}