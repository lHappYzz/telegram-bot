<?php

namespace App\Commands;

use App\Bot;
use Boot\Src\Abstracts\Telegram;
use Boot\Traits\DirectoryHelpers;
use ReflectionClass;

class HelpCommand extends BaseCommand
{
    use DirectoryHelpers;

    protected string $description = 'Represents available bot commands.';
    protected string $signature = '/help';

    public function boot(Bot $bot, array $parameters = []): void
    {
        $message = 'List of available Commands:' . PHP_EOL;
        $classedInCommandsDir = $this->getCommandsInTheCommandDir();
        foreach ($classedInCommandsDir as $commandClass) {
            $reflection = new ReflectionClass(Telegram::COMMANDS_NAMESPACE.substr($commandClass, 0, -4));
            $command = $this->getClassInstance($reflection->getName());
            if ($command instanceof BaseCommand) {
                $message .= $command->getSignature().' - '.$command->getDescription().PHP_EOL;
            }
        }
        $bot->sendMessage($message, $bot->getChat());
    }
}