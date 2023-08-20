<?php

namespace Boot;

use Boot\Interfaces\PermissionManager;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Entities\TelegramUser;
use Boot\Traits\DirectoryHelpers;

class Gate implements PermissionManager
{
    use DirectoryHelpers;

    /**
     * @param TelegramUser $telegramUser
     * @param string $abstract
     * @return bool
     */
    public function hasCommandAccess(TelegramUser $telegramUser, string $abstract): bool
    {
        /** @var BaseCommand $command */
        $command = $this->getClassInstance($abstract);

        if (!empty($allowed = $command->getAllowedUsers())) {
            return in_array($telegramUser->getId(), $allowed);
        }

        return true;
    }
}