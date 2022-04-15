<?php

namespace Boot\Src;

abstract class Singleton
{
    protected function __construct()
    {
        //
    }

    /**
     * @return static
     */
    final public static function getInstance(): static
    {
        static $aoInstance = array();

        $calledClassName = static::class;

        if (!isset ($aoInstance[$calledClassName])) {
            $aoInstance[$calledClassName] = new $calledClassName();
        }

        return $aoInstance[$calledClassName];
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