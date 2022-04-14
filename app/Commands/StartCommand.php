<?php

namespace App\Commands;

use App\Bot;

class StartCommand extends BaseCommand
{
    protected string $description = 'Greetings to the user.';
    protected string $signature = '/start';

    protected static ?BaseCommand $instance = null;

    public function boot(Bot $bot): void
    {
        $userCode = $bot->getMessage()->getFrom()->getID();
        $helloMessage = 'Hello, I was created to make students\' lives more comfortable by sending them study schedule.';
        $bot->sendMessage($helloMessage, $bot->getChat());
        $bot->sendMessage('Your code: ' . $userCode, $bot->getChat());
    }
}