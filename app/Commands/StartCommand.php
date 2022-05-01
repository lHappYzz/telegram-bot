<?php

namespace App\Commands;

use App\Bot;
use App\Records\ChatRecord;
use App\Records\StatusRecord;
use App\Records\UserRecord;
use Boot\Src\TelegramChat;
use Boot\Src\TelegramUser;

class StartCommand extends BaseCommand
{
    protected string $description = 'Greetings to the user.';
    protected string $signature = '/start';

    public function boot(Bot $bot, array $parameters = []): void
    {
        $telegramChat = $bot->getChat();
        $telegramUser = $bot->getMessage()->getFrom();

        if (!$this->checkUserInDB($telegramUser) && $this->createUserRecord($telegramUser)) {
            $helloMessage = 'Hello! Nice to meet you, ' . $telegramUser->getFirstName();
            $bot->sendMessage($helloMessage, $telegramChat);
        }

        if (!$this->checkChatInDB($telegramChat)) {
            $this->createChatRecord($telegramChat, $telegramUser);
            return;
        }

        $bot->sendMessage('👋🏻', $telegramChat);
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