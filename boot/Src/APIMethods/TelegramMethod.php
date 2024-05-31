<?php

namespace Boot\Src\APIMethods;

use Boot\Interfaces\TelegramRequirementsValidate;
use Boot\Src\Abstracts\JsonSerializableEntity;

abstract class TelegramMethod extends JsonSerializableEntity implements TelegramRequirementsValidate
{
    /**
     * Return telegram-side method name.
     *
     * @return string
     */
    abstract public function getMethodName(): string;
}