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
     * @var string $token
     */
    private string $token;

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
                return $this->telegramFacade->{$name}($this->token, ...$arguments);
            }
        } catch (ReflectionException) {}

        throw new Error("Call to undefined method" . self::class . "::$name().");
    }

    /**
     * @param TelegramFacade $telegramFacade
     * @param string $token
     * @return void
     */
    private function setTelegramFacade(TelegramFacade $telegramFacade, string $token): void
    {
        $this->telegramFacade = $telegramFacade;
        $this->token = $token;
    }
}