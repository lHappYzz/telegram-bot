<?php

namespace Boot\Interfaces;

use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Entities\TelegramUser;

interface PermissionManager
{
    /**
     * @param TelegramUser $telegramUser
     * @param BaseCommand $command
     * @return bool
     */
    public function hasCommandAccess(TelegramUser $telegramUser, BaseCommand $command): bool;
}