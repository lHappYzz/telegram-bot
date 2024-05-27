<?php

namespace Boot\Src\APIMethods;

use Boot\Src\Abstracts\JsonSerializableEntity;
use Boot\Src\Exceptions\TelegramMethod\TelegramMethodException;

abstract class TelegramMethod extends JsonSerializableEntity
{
    /**
     * Validate fields to be sent according to telegram requirements.
     *
     * @return void
     * @throws TelegramMethodException
     */
    abstract public function validate(): void;

    /**
     * Return telegram-side method name.
     *
     * @return string
     */
    abstract public function getMethodName(): string;
}