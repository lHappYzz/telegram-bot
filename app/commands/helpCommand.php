<?php


namespace App\Commands;

use App\Bot;
use Boot\Traits\Helpers;

class helpCommand extends baseCommand
{
    use Helpers;

    protected $description = 'Represents available bot commands.';
    protected $signature = '/help';

    protected static $instance = null;

    public function boot(Bot $bot)
    {
        $message = 'List of available commands:' . PHP_EOL;
        $classedInCommandsDir = $this->getCommandsInTheCommandDir();
        foreach ($classedInCommandsDir as $commandClass) {
            $command = $this->getCommandClassInstance(substr($commandClass, 0, -4));
            if ($command instanceof baseCommand) {
                $message .= '<'.$command->getSignature().'> - '.$command->getDescription().PHP_EOL;
            }
        }
        $bot->sendMessage($message);
    }
}