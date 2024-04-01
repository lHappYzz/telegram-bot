<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Traits\WithInputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedsticker
 */
class InlineQueryResultCachedSticker extends InlineQueryResult
{
    use WithInputMessageContent;

    /**
     * @param string $id
     * @param string $stickerFileId
     */
    public function __construct(
        string $id,
        protected string $stickerFileId
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'sticker';
    }
}