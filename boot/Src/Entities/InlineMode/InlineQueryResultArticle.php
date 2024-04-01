<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Traits\WithInputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultarticle
 */
class InlineQueryResultArticle extends InlineQueryResult
{
    use WithInputMessageContent;

    /**
     * @param string $id
     * @param string $title
     * @param string|null $url
     * @param bool|null $hideUrl
     * @param string|null $description
     * @param string|null $thumbnailUrl
     * @param int|null $thumbnailWidth
     * @param int|null $thumbnailHeight
     */
    public function __construct(
        string $id,
        protected string $title,
        protected ?string $url = null,
        protected ?bool $hideUrl = null,
        protected ?string $description = null,
        protected ?string $thumbnailUrl = null,
        protected ?int $thumbnailWidth = null,
        protected ?int $thumbnailHeight = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'article';
    }
}