<?php

require_once "vendor/autoload.php";

use Boot\Application;
use Boot\Log\Logger;

try {
    $app = new Application();
    $bot = $app->boot();
} catch (Exception $e) {
    Logger::logException($e, Logger::LEVEL_ERROR);
    die();
}
$bot->handle();
