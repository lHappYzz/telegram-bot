<?php

namespace Boot\Traits;

trait helpers {

    public function getCommandClassInstance($className) {
        $commandsNamespace = '\\App\\Commands\\';

        $availableCommands = $this->getCommandsInTheCommandDir();
        foreach ($availableCommands as $availableCommand) {
            if ($availableCommand === $className.'.php' && $availableCommand !== 'baseCommand.php') {
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

    /**
     * Convert string camel case style to snake case
     * @param string $input
     * @return string
     */
    public function camelCaseToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * Convert string snake case style to camel case
     * @param string $input
     * @return string
     */
    public function snakeCaseToCamelCase(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }
}