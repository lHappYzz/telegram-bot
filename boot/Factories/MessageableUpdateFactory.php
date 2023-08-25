<?php

namespace Boot\Factories;

use Boot\Src\Entities\TelegramMessage;
use Boot\Src\Update;

class MessageableUpdateFactory extends UpdateFactory
{
    /**
     * @return Update
     */
    function createUpdate(): Update
    {
        return new Update(
            $this->updateId,
            container(TelegramMessage::class, $this->components),
        );
    }
}