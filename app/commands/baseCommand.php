<?php


namespace App\Commands;


use App\Bot;

class baseCommand
{
    public function boot(Bot $bot) {
        $bot->sendMessage('Hello from base command');
    }
}