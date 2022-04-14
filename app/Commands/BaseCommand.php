<?php

namespace App\Commands;

use App\Bot;

abstract class BaseCommand
{
    abstract public function boot(Bot $bot): void;

    public static function getInstance(): static
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }
}