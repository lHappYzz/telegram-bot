<?php

namespace Boot\Interfaces;

use Boot\Src\Entities\TelegramChat;

interface CallbackableQueryEntity
{
    /**
     * Returns the chat from which the callback received
     *
     * @return TelegramChat
     */
    public function getChat(): TelegramChat;
}