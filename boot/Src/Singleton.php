<?php

namespace Boot\Src;

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

    final private function __clone()
    {
        //
    }

    final private function __wakeup()
    {
        //
    }
}