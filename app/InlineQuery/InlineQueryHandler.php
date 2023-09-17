<?php

namespace App\InlineQuery;

use App\Bot;
use Boot\Src\Entities\InlineMode\InlineQueryResultArticle;
use Boot\Src\Entities\InlineQuery;
use Boot\Src\Entities\MessageEntity;

class InlineQueryHandler
{
    public function __construct(protected InlineQuery $inlineQuery, protected Bot $bot) {}

    public function handle(): void
    {
        $inlineQueryResult = new InlineQueryResultArticle(1, 'Title');

        $inlineQueryResult
            ->addText('Message text')
            ->addMessageEntity(
                new MessageEntity(
                    MessageEntity::SPOILER_TYPE,
                    0,
                    6
                )
            );

        $this->bot->answerInlineQuery($this->inlineQuery->getId(), [$inlineQueryResult], 10);
    }
}