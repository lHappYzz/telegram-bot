<?php

namespace Boot\Traits;

trait helpers {
    public function getCommandClassInstance($className) {
        $commandsNamespace = '\\App\\Commands\\';

        $availableCommands = $this->getCommandsInTheCommandDir();
        foreach ($availableCommands as $availableCommand) {
            if ($availableCommand == $className.'.php' && $availableCommand != 'baseCommand.php') {
                $fullPath = $commandsNamespace.$className;
                return $fullPath::getInstance();
            }
        }
        return null;
    }
    public function getCommandsInTheCommandDir() {
        $commands = scandir('app'.DIRECTORY_SEPARATOR.'Commands');
        foreach ($commands as $key => $command) {
            if (!is_file('app'.DIRECTORY_SEPARATOR.'Commands'.DIRECTORY_SEPARATOR.$command)) {
                unset($commands[$key]);
            }
        }
        return array_values($commands);
    }
}