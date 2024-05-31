<?php

namespace Boot\Src\APIMethods;

use Boot\Src\Entities\InlineMode\InlineQueryResult;
use Boot\Src\Exceptions\TelegramMethod\InvalidTelegramMethodFieldException;

class AnswerInlineQuery extends TelegramMethod
{
    /**
     * @param string $inlineQueryId
     * @param InlineQueryResult[] $results
     * @param int|null $cacheTime
     * @param bool|null $isPersonal
     * @param string|null $nextOffset
     */
    public function __construct(
        public string $inlineQueryId,
        public array $results,
        public ?int $cacheTime = null,
        public ?bool $isPersonal = null,
        public ?string $nextOffset = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if (count($this->results) > 50) {
            throw new InvalidTelegramMethodFieldException('No more than 50 results per query are allowed.');
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'answerInlineQuery';
    }

    public function jsonSerialize(): array
    {
        $serialized = parent::jsonSerialize();

        $serialized['results'] = json_encode($serialized['results']);

        return $serialized;
    }
}