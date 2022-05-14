<?php

namespace Boot\Interfaces;

use App\Bot;

interface ChatState
{
    public function handle(Bot $bot): void;
}