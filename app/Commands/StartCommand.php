<?php

namespace App\Commands;

use App\Bot;
use App\Records\ChatRecord;
use App\Records\StatusRecord;
use App\Records\UserRecord;
use Boot\Src\Entities\TelegramChat;
use Boot\Src\Entities\TelegramMessage;
use Boot\Src\Entities\TelegramUser;

class StartCommand extends BaseCommand
{
    protected string $description = 'Greetings to the user.';
    protected string $signature = '/start';

    public function boot(Bot $bot, TelegramMessage $telegramMessage, array $parameters = []): void
    {
        $telegramUser = $telegramMessage->getFrom();

        if (!$this->checkUserInDB($telegramUser) && $this->createUserRecord($telegramUser)) {
            $helloMessage = 'Hello! Nice to meet you, ' . $telegramUser->getFirstName();
            $bot->sendMessage($helloMessage, $telegramMessage->getChat());
        }

        if (!$this->checkChatInDB($telegramMessage->getChat())) {
            $this->createChatRecord($telegramMessage->getChat(), $telegramUser);
            return;
        }
        $bot->sendMessage('👋🏻', $telegramMessage->getChat());
    }

    private function checkUserInDB(TelegramUser $telegramUser): null|UserRecord
    {
        return UserRecord::fetch($telegramUser->getId());
    }

    private function checkChatInDB(TelegramChat $telegramChat): null|ChatRecord
    {
        return ChatRecord::fetch($telegramChat->getId());
    }

    private function createUserRecord(TelegramUser $telegramUser): bool
    {
        return UserRecord::createFrom($telegramUser)->create();
    }

    private function createChatRecord(TelegramChat $telegramChat, TelegramUser $telegramUser): bool
    {
        return ChatRecord::createFrom($telegramChat)
            ->with([
                'status_id' => StatusRecord::STATUS_DEFAULT,
                'user_id' => $telegramUser->getId()
            ])->create();
    }
}