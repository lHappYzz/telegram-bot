<?php

namespace Boot;

use App\Bot;
use App\Config\Config;
use Boot\Src\Telegram;
use Exception;

class Application extends Telegram
{
    /**
     * Starts the application by doing some things like getting the configuration or parsing telegram request
     * In success case new bot instance will be returned else an exception will be thrown
     *
     * @return Bot
     * @throws Exception
     */
    public function boot(): Bot
    {
        if (!Config::exists()) {
            throw new Exception('Missing application configuration file');
        }

        date_default_timezone_set(Config::timezone());

        return new Bot($this);
    }
}