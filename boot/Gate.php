<?php

namespace Boot;

use Boot\Interfaces\PermissionManager;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Entities\TelegramUser;

class Gate implements PermissionManager
{
    /**
     * @param TelegramUser $telegramUser
     * @param BaseCommand $command
     * @return bool
     */
    public function hasCommandAccess(TelegramUser $telegramUser, BaseCommand $command): bool
    {
        if (!empty($allowed = $command->getAllowedUsers())) {
            return in_array($telegramUser->getId(), $allowed);
        }

        return true;
    }
}