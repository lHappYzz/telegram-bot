<?php

namespace Boot\Src\Abstracts;

use App\Bot;
use Boot\Src\Entities\TelegramMessage;

abstract class BaseCommand extends Singleton
{
    /** @var string */
    protected string $description = '';

    /** @var string */
    protected string $signature = '';

    /**
     * @param Bot $bot
     * @param TelegramMessage $telegramMessage
     * @param array $parameters
     * @return void
     */
    abstract public function boot(
        Bot $bot,
        TelegramMessage $telegramMessage,
        array $parameters = []
    ): void;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * Contains ids of users authorized for command. If empty array is returned command is public.
     * @return array
     */
    public function getAllowedUsers(): array
    {
        return [];
    }
}