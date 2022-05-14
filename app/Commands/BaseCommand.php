<?php

namespace App\Commands;

use App\Bot;
use Boot\Src\Abstracts\Singleton;

abstract class BaseCommand extends Singleton
{
    abstract public function boot(Bot $bot, array $parameters = []): void;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }
}