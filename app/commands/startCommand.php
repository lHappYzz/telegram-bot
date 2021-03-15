<?php


namespace App\Commands;


use App\Bot;

class startCommand extends baseCommand
{
    protected $description = 'Greetings to the user.';
    protected $signature = '/start';

    protected static $instance = null;

    public function boot(Bot $bot)
    {
        $helloMessage = 'Hello, I was created to make students\' lives more comfortable by sending them study schedule.';
        $bot->sendMessage($helloMessage);
    }
}