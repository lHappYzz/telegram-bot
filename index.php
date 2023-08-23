<?php

require_once "vendor/autoload.php";

use Boot\Application;
use Boot\Container;
use Boot\Log\Logger;

try {
    (new Application(new Container()))->boot();
} catch (Exception $e) {
    Logger::logException($e, Logger::LEVEL_ERROR);
}
