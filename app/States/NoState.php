<?php

namespace App\States;

use App\Bot;
use Boot\Src\Abstracts\State;

class NoState extends State
{
    public function handle(Bot $bot): void
    {
        //handle chats with no status (more likely this user has never before used this bot)
    }
}