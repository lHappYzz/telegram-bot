<?php

namespace App\Commands;

use App\bot;

class startCommand extends baseCommand
{
    protected string $description = 'Greetings to the user.';
    protected string $signature = '/start';

    protected static ?baseCommand $instance = null;

    public function boot(bot $bot): void
    {
        $userCode = $bot->getMessage()->getFrom()->getID();
        $helloMessage = 'Hello, I was created to make students\' lives more comfortable by sending them study schedule.';
        $bot->sendMessage($helloMessage);
        $bot->sendMessage('Your code: ' . $userCode);
    }
}