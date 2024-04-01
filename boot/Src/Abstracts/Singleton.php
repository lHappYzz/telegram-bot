<?php

namespace Boot\Src\Abstracts;

use Boot\Container;

/**
 * @todo Remove pattern and use dependency injection through container
 * @see Container
 * @deprecated Do not use
 */
abstract class Singleton
{
    private static array $aoInstance = [];

    protected function __construct()
    {
        //
    }

    /**
     * @return static
     */
    final public static function getInstance(): static
    {
        $calledClassName = static::class;

        if (!isset (self::$aoInstance[$calledClassName])) {
            self::$aoInstance[$calledClassName] = new $calledClassName();
        }

        return self::$aoInstance[$calledClassName];
    }

    private function __clone()
    {
        //
    }

    private function __wakeup()
    {
        //
    }
}