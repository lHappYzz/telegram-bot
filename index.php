<?php

require_once "vendor/autoload.php";

use Boot\Application;
use Boot\Log\Logger;

try {
    (new Application())->boot();
} catch (Exception $e) {
    Logger::logException($e, Logger::LEVEL_ERROR);
}
