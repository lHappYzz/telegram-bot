<?php

namespace App\Commands;

use App\Bot;
use Boot\Traits\Helpers;

class HelpCommand extends BaseCommand
{
    use Helpers;

    protected string $description = 'Represents available bot commands.';
    protected string $signature = '/help';

    protected static ?BaseCommand $instance = null;

    public function boot(Bot $bot): void
    {
        $message = 'List of available Commands:' . PHP_EOL;
        $classedInCommandsDir = $this->getCommandsInTheCommandDir();
        foreach ($classedInCommandsDir as $commandClass) {
            $command = $this->getCommandClassInstance(substr($commandClass, 0, -4));
            if ($command instanceof BaseCommand) {
                $message .= $command->getSignature().' - '.$command->getDescription().PHP_EOL;
            }
        }
        $bot->sendMessage($message, $bot->getChat());
    }
}