<?php

namespace App\States;

use App\Bot;
use App\Records\StatusRecord;
use Boot\Src\Abstracts\State;

class DefaultState extends State
{
    public function handle(Bot $bot): void
    {
        $bot->sendMessage('Current state: Default', $bot->getChat());

        $statusRecord = StatusRecord::fetch(StatusRecord::POST_SUGGESTION);
        $this->telegramChat->setStatus($statusRecord);

        $bot->sendMessage('State changed to: post_suggestion', $bot->getChat());
    }
}