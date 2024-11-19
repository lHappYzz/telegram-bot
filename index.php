<?php

require_once "vendor/autoload.php";

use Boot\Application;
use Boot\Container;

$container = new Container();

$application = new Application($container);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application->boot();
}

//Use this row to set up webhook and then remove
//$application->bot->setWebhook();
