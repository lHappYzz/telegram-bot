<?php

namespace Boot;

use App\Bot;
use Exception;

class Application {

    /**
     * @var $config
     * The application configuration
     */
    private $config;

    /**
     * Starts the application by doing some things like getting the configuration or parsing telegram request
     * In success case new bot instance will be returned else an exception will be thrown
     *
     * @return Bot
     * @throws Exception
     */
    public function boot() {
        $this->config = parse_ini_file('app/app.ini');
        if (!$this->config) throw new Exception('Missing application configuration file');
        date_default_timezone_set($this->config['timezone']);
        return new Bot($this->config);
    }

}