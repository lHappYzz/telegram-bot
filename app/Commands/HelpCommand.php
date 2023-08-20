<?php

namespace App\Commands;

use App\Bot;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Abstracts\Telegram;
use Boot\Src\Entities\TelegramMessage;
use Boot\Traits\DirectoryHelpers;

class HelpCommand extends BaseCommand
{
    use DirectoryHelpers;

    protected string $description = 'Represents available bot commands.';
    protected string $signature = '/help';

    //TODO: use permission check through a permission manager
    public function boot(Bot $bot, TelegramMessage $telegramMessage, array $parameters = []): void
    {
        $message = 'List of available Commands:' . PHP_EOL;
        $classedInCommandsDir = $this->getCommandsInTheCommandDir();
        foreach ($classedInCommandsDir as $commandClass) {
            $command = $this->getClassInstance(Telegram::COMMANDS_NAMESPACE . substr($commandClass, 0, -4));
            if ($command instanceof BaseCommand) {
                if (
                    empty($command->getAllowedUsers()) ||
                    in_array($telegramMessage->getFrom()->getId(), $command->getAllowedUsers())
                ) {
                    $message .= $command->getSignature() . ' - ' . $command->getDescription().PHP_EOL;
                }
            }
        }
        $bot->sendMessage($message, $telegramMessage->getChat());
    }
}