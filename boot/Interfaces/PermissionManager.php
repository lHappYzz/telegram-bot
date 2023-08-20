<?php

namespace Boot\Interfaces;

use Boot\Src\Entities\TelegramUser;

interface PermissionManager
{
    /**
     * @param TelegramUser $telegramUser
     * @param string $abstract
     * @return bool
     */
    public function hasCommandAccess(TelegramUser $telegramUser, string $abstract): bool;
}