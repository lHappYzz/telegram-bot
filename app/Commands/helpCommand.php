<?php

namespace App\Commands;

use App\bot;
use Boot\Traits\helpers;

class helpCommand extends baseCommand
{
    use helpers;

    protected string $description = 'Represents available bot commands.';
    protected string $signature = '/help';

    protected static ?baseCommand $instance = null;

    public function boot(bot $bot): void
    {
        $message = 'List of available Commands:' . PHP_EOL;
        $classedInCommandsDir = $this->getCommandsInTheCommandDir();
        foreach ($classedInCommandsDir as $commandClass) {
            $command = $this->getCommandClassInstance(substr($commandClass, 0, -4));
            if ($command instanceof baseCommand) {
                $message .= $command->getSignature().' - '.$command->getDescription().PHP_EOL;
            }
        }
        $bot->sendMessage($message, $bot->getChat());
    }
}