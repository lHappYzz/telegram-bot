<?php

require_once "vendor/autoload.php";

use Boot\application;

try {
    $app = new application();
    $bot = $app->boot();
} catch (Exception $e) {
    die($e->getMessage());
}
$bot->handle();
