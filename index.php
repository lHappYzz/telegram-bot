<?php

require_once "vendor/autoload.php";

use Boot\Application;
use Boot\Container;
use Boot\Log\Logger;

$container = new Container();

$application = new Application($container);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $application->boot();
    } catch (Exception $e) {
        Logger::logException($e, Logger::LEVEL_ERROR);
    }
}

//Use this row to set up webhook and then remove
//$application->bot->setWebhook();
