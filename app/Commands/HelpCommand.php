<?php

namespace App\Commands;

use App\Bot;
use Boot\Interfaces\ContainerInterface;
use Boot\Interfaces\PermissionManager;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Abstracts\Telegram;
use Boot\Src\Entities\TelegramMessage;
use Boot\Traits\DirectoryHelpers;

class HelpCommand extends BaseCommand
{
    use DirectoryHelpers;

    protected function __construct(protected PermissionManager $permissionManager, protected ContainerInterface $container) {}

    protected string $description = 'Represents available bot commands.';
    protected string $signature = '/help';

    public function boot(Bot $bot, TelegramMessage $telegramMessage, array $parameters = []): void
    {
        $message = 'List of available Commands:' . PHP_EOL;

        foreach ($this->getFiles('app' . DIRECTORY_SEPARATOR . 'Commands') as $file) {
            /** @var BaseCommand $command */
            $command = $this->container->get(Telegram::COMMANDS_NAMESPACE . $this->removeFileExtension($file));

            if ($this->permissionManager->hasCommandAccess($telegramMessage->getFrom(), $command)) {
                $message .= $command->getSignature() . ' - ' . $command->getDescription().PHP_EOL;
            }
        }

        $bot->sendMessage($message, $telegramMessage->getChat()->getId());
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function removeFileExtension(string $fileName): string
    {
        return substr($fileName, 0, -4);
    }
}