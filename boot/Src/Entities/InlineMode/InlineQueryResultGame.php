<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultgame
 */
class InlineQueryResultGame extends InlineQueryResult
{
    /**
     * @param string $id
     * @param string $gameShortName
     */
    public function __construct(
        string $id,
        protected string $gameShortName,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'game';
    }
}