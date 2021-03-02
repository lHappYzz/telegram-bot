<?php

require_once "vendor/autoload.php";

use Boot\Application;

try {
    $app = new Application();
    $bot = $app->boot();
} catch (Exception $e) {
    die($e->getMessage());
}
$bot->handle();
