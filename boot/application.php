<?php

namespace Boot;

use App\bot;
use Exception;

class application {

    /**
     * @var $config
     * The application configuration
     */
    public static $config;

    /**
     * Starts the application by doing some things like getting the configuration or parsing telegram request
     * In success case new bot instance will be returned else an exception will be thrown
     *
     * @return bot
     * @throws Exception
     */
    public function boot() {
        self::$config = parse_ini_file('app/app.ini');
        if (!self::$config) throw new Exception('Missing application configuration file');
        date_default_timezone_set(self::$config['timezone']);
        return new bot(self::$config);
    }

    public static function log($whatToLog) {
        file_put_contents("systemlogs.txt", date('H:i:s')." ->".gettype($whatToLog)."<- ".print_r(($whatToLog), 1) . "\n*******\n\n");
    }

}