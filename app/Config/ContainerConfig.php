<?php

namespace App\Config;

use App\Bot;
use Boot\Container;
use Boot\Gate;
use Boot\Interfaces\PermissionManager;

class ContainerConfig
{
    /**
     * @param Container $container
     */
    public function __construct(protected Container $container) {}

    /**
     * Provide abstracts with their concretes so container can resolve dependencies when needed
     *
     * @return void
     */
    public function bindings(): void
    {
        $this->container->singleton(Bot::class);
        $this->container->singleton(PermissionManager::class, Gate::class);

        $this
            ->container
            ->when(Bot::class)
            ->needs('token')
            ->give(Config::bot()['bot_token']);
    }
}