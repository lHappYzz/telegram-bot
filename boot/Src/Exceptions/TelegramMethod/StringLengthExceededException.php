<?php

namespace Boot\Src\Exceptions\TelegramMethod;

use Throwable;

class StringLengthExceededException extends InvalidTelegramMethodFieldException
{
    public function __construct(string $fieldName, int $exceededNumber, string $methodName, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("$fieldName must be at most $exceededNumber characters when using $methodName method.", $code, $previous);
    }
}