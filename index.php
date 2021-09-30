<?php

require_once "vendor/autoload.php";

use Boot\application;

try {
    $app = new application();
    $bot = $app->boot();
} catch (Exception $e) {
    application::log($e->getMessage()); die;
}
$bot->handle();
