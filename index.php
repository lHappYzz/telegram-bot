<?php

require_once "vendor/autoload.php";

use Boot\application;
use Boot\Log\Logger;

try {
    $app = new application();
    $bot = $app->boot();
} catch (Exception $e) {
    Logger::logException($e, Logger::LEVEL_ERROR);
    die();
}
$bot->handle();
