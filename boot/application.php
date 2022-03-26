<?php

namespace Boot;

use App\bot;
use App\Config\Config;
use Exception;

class application {
    /**
     * Starts the application by doing some things like getting the configuration or parsing telegram request
     * In success case new bot instance will be returned else an exception will be thrown
     *
     * @return bot
     * @throws Exception
     */
    public function boot(): bot
    {
        if (!Config::exists()) {
            throw new Exception('Missing application configuration file');
        }

        date_default_timezone_set(Config::timezone());

        return new bot();
    }

    public static function log($whatToLog): void
    {
        file_put_contents("systemlogs.txt", date('H:i:s')." ->".gettype($whatToLog)."<- ".print_r(($whatToLog), 1) . "\n*******\n\n");
    }

}