<?php

namespace Boot\Traits;

use Boot\Facades\TelegramFacade;
use Error;
use ReflectionException;
use ReflectionMethod;

trait DynamicTelegramCall
{
    /**
     * @var TelegramFacade $telegramFacade
     */
    private TelegramFacade $telegramFacade;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        try {
            $method = new ReflectionMethod(TelegramFacade::class, $name);
            if ($method->isPublic()) {
                return $this->telegramFacade->{$name}(...$arguments);
            }
        } catch (ReflectionException) {}

        throw new Error("Call to undefined method" . self::class . "::$name().");
    }
}