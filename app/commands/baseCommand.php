<?php


namespace App\Commands;


use App\Bot;

abstract class baseCommand
{
    abstract public function boot(Bot $bot);

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getSignature() {
        return $this->signature;
    }
}