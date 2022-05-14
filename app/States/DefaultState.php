<?php

namespace App\States;

use App\Bot;
use Boot\Src\Abstracts\State;

class DefaultState extends State
{
    public function handle(Bot $bot): void
    {
        //handle chats with default status
    }
}